<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Dependencia;
use App\Models\Turnos_Dependencias;
use App\Models\Turnos_Tramites;
use App\Models\Turnos_Dependencias_Reservas;
use App\Models\Tramite;
use App\Models\Documento;
use App\Models\Feriado;
use App\Models\Dependencia_Tramite;
use App\Http\Requests\SearchTurno;
use App\Http\Requests\StoreTurno;
use App\Mail\NuevoTramite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\IntegrantesImport;

class TramitesController extends Controller
{
    public function __construct()
    {

    }

    protected function index(Request $request)
    {
        //Se obtiene las dependencias que tienen emiten turnos
        $dependencias = Dependencia::getDependenciasConTurnos();

        //dd($dependencias);

        $aux_dependencia_id = $request->session()->get('dependencia_id');
        
        if (isset($aux_dependencia_id)) {
            $dependencia_id = $request->session()->get('dependencia_id');
        } else {
            $dependencia_id = 0;
        }

        $aux_dependencia_tramite_id = $request->session()->get('dependencia_tramite_id');
        if (isset($aux_dependencia_tramite_id)) {
            $dependencia_tramite_id = $request->session()->get('dependencia_tramite_id');
        } else {
            $dependencia_tramite_id = 0;
        }

        return view('frontend.form-turno-paso1')->with(compact('dependencias', 'dependencia_id', 'dependencia_tramite_id'));
    }

    public function paso2(Request $request)
    {
        $input = $request->all();

        

        //Se administran los datos de session
        if (isset($input['dependencia_id'])) {
            $request->session()->put('dependencia_id', $input['dependencia_id']);
        }

        if (isset($input['dependencia_tramite_id'])) {
            $request->session()->put('dependencia_tramite_id', $input['dependencia_tramite_id']);
        }

        $dependencia_tramite_id = $request->session()->get('dependencia_tramite_id');
        if (!$this->checkAvailability($dependencia_tramite_id)) {
            return redirect()->route('tramite.index')->with('error', 'No hay turnos disponibles para este trámite.');
        }

        $turno_tramite = Turnos_Tramites::where('dependencia_tramite_id', $dependencia_tramite_id)
            ->where('activo', true)
            ->where('fecha_hasta', '>=', Carbon::today())
            ->orderByDesc('id')
            ->first();

        if (!$turno_tramite) {
            return redirect()->route('tramite.index')->with('error', 'No hay turnos disponibles para este trámite.');
        }

        if ($turno_tramite->fecha_desde->format('Y-m-d') > Carbon::today()->format('Y-m-d')) {
            $fecha_desde = $turno_tramite->fecha_desde->format('d/m/Y');
        } else {
            $fecha_desde = Carbon::today()->format('d/m/Y');
        }

        $request->session()->put('turno_tramite_id', $turno_tramite->id);

        $turno_fecha = $request->session()->get('turno_fecha', 0);
        $turno_hora = $request->session()->get('turno_hora', 0);
        
        $feriados = Feriado::whereDate('fecha', '>=', Carbon::today())->get()->map(function ($feriado) {
            return $feriado->fecha;
        })->toArray();
        $feriados_text = Feriado::whereDate('fecha', '>=', Carbon::today())->get();

        $turno_dependencia = $turno_tramite;
        $dependencia_tramite = Dependencia_Tramite::with('dependencia')->find($dependencia_tramite_id);
        $dependencia = $dependencia_tramite ? $dependencia_tramite->dependencia : Dependencia::find($request->session()->get('dependencia_id'));

        $dias_activos = [
            'lunes' => false,
            'martes' => false,
            'miercoles' => false,
            'jueves' => false,
            'viernes' => false,
            'sabado' => false,
            'domingo' => false
        ];
        foreach ($turno_tramite->turnosHorarios as $h) {
            if ($h->activo) {
                if ($h->lunes) $dias_activos['lunes'] = true;
                if ($h->martes) $dias_activos['martes'] = true;
                if ($h->miercoles) $dias_activos['miercoles'] = true;
                if ($h->jueves) $dias_activos['jueves'] = true;
                if ($h->viernes) $dias_activos['viernes'] = true;
                if ($h->sabado) $dias_activos['sabado'] = true;
                if ($h->domingo) $dias_activos['domingo'] = true;
            }
        }

        return view('frontend.form-turno-paso2')->with(compact('turno_dependencia', 'dependencia', 'dependencia_tramite', 'turno_fecha', 'turno_hora', 'fecha_desde', 'feriados','feriados_text', 'dias_activos'));
    }

    public function paso3(Request $request)
    {
        try { 
            $input = $request->all();

            if (!empty($input['turno_fecha'])) {
                $request->session()->put('turno_fecha', $input['turno_fecha']);
            }
            if (!empty($input['turno_hora'])) {
                $request->session()->put('turno_hora', $input['turno_hora']);
            }

            $dependencia_id = $request->session()->get('dependencia_id');
            $dependencia_tramite_id = $request->session()->get('dependencia_tramite_id');
            $turno_fecha = $request->session()->get('turno_fecha');
            $turno_hora = $request->session()->get('turno_hora');

            if (empty($turno_fecha) || empty($turno_hora) || strpos($turno_hora, '|') === false) {
                return redirect()->route('tramite.paso2')->with('error', 'Por favor seleccione un día en el calendario y un horario disponible antes de continuar.');
            }

            list($hora_raw) = explode('|', $turno_hora);
            $turno_hora_display = substr($hora_raw, 0, 5);

            $dependenciaModel = Dependencia::find($dependencia_id);
            if (!$dependenciaModel) {
                return redirect()->route('tramite.index');
            }
            $dependencia = $dependenciaModel->toArray();
            $dependencia_tramite = Dependencia_Tramite::find($dependencia_tramite_id);

            $usuario = Auth::user();

            return view('frontend.form-turno-paso3')->with(compact('dependencia', 'dependencia_tramite', 'turno_fecha', 'turno_hora', 'turno_hora_display', 'usuario'));
        } catch (\Exception $e) {
            return redirect(route('tramite.index'));
        }
    }

    private function autorizarReservaEnSesion(Request $request, Turnos_Dependencias_Reservas $reserva): void
    {
        $autorizadas = $request->session()->get('reservas_autorizadas', []);
        $autorizadas[$reserva->id] = $reserva->codigo;
        $request->session()->put('reservas_autorizadas', $autorizadas);
    }

    private function esAdminOOperador(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['ADMINISTRADOR', 'OPERADOR']);
    }

    private function esTitularAutenticado(Turnos_Dependencias_Reservas $reserva): bool
    {
        return Auth::check()
            && !empty($reserva->email)
            && strcasecmp(trim((string) Auth::user()->email), trim((string) $reserva->email)) === 0;
    }

    private function puedeAccederReserva(Request $request, Turnos_Dependencias_Reservas $reserva): bool
    {
        if ($this->esAdminOOperador() || $this->esTitularAutenticado($reserva)) {
            return true;
        }

        $autorizadas = $request->session()->get('reservas_autorizadas', []);
        return isset($autorizadas[$reserva->id]) && $autorizadas[$reserva->id] === $reserva->codigo;
    }

    public function guardar(StoreTurno $request)
    {
        $input = $request->all();

        if (!$request->session()->has('turno_hora') || !$request->session()->has('turno_fecha')) {
            return redirect()->route('tramite.index')->with('error', 'La sesión ha expirado o los datos del turno son inválidos. Por favor, intente de nuevo.');
        }

        $session_turno_hora = $request->session()->get('turno_hora');
        
        if (strpos($session_turno_hora, '|') === false) {
            return redirect()->route('tramite.index')->with('error', 'Formato de hora inválido. Por favor, seleccione un turno nuevamente.');
        }

        list($turno_hora, $turno_horario_id) = explode('|', $session_turno_hora);

        $dependencia_tramite_id = $request->session()->get('dependencia_tramite_id');
        $dependencia_tramite = Dependencia_Tramite::find($dependencia_tramite_id);
        if (!$dependencia_tramite) {
            return redirect()->route('tramite.index')->with('error', 'El trámite seleccionado ya no está disponible.');
        }

        try {
            $fecha = Carbon::createFromFormat('d/m/Y', $request->session()->get('turno_fecha'))->startOfDay();
            $fecha_hora = Carbon::createFromFormat('d/m/Y H:i:s', $request->session()->get('turno_fecha').' '.$turno_hora);
        } catch (\Exception $e) {
            return redirect()->route('tramite.index')->with('error', 'La fecha u horario del turno es inválido. Por favor seleccione nuevamente.');
        }

        if ($fecha_hora->isPast()) {
            return redirect()->route('tramite.paso2')->with('error', 'El horario seleccionado ya pasó. Por favor elija una nueva fecha u horario.');
        }

        // Verificar que la fecha no sea feriado activo
        $esFeriado = Feriado::where('activo', 1)
            ->where('fecha', $fecha->format('d/m/Y'))
            ->exists();
        if ($esFeriado) {
            return redirect()->route('tramite.paso2')->with('error', 'La fecha seleccionada corresponde a un feriado sin atención.');
        }

        $usaCupoPorDelegacion = $dependencia_tramite->usaCupoPorDelegacion();
        $solicitadas = (int) $request->input('cantidad_personas', 1);
        $dniNormalizado = Turnos_Dependencias_Reservas::normalizarDni($input['dni']);

        // Validación de Límite Máximo configurado por el Administrador
        if ($dependencia_tramite->max_personas_reserva && $solicitadas > $dependencia_tramite->max_personas_reserva) {
            return back()->withInput()->with('error', "La cantidad de personas solicitadas ({$solicitadas}) supera el límite máximo permitido para este trámite ({$dependencia_tramite->max_personas_reserva} personas).");
        }

        // Validación de Límite Mínimo configurado para reservas grupales / institucionales
        if (($request->boolean('es_grupal') || $usaCupoPorDelegacion) && $dependencia_tramite->min_personas_reserva && $solicitadas < $dependencia_tramite->min_personas_reserva) {
            return back()->withInput()->with('error', "La cantidad de personas solicitadas ({$solicitadas}) es menor al mínimo requerido para esta modalidad ({$dependencia_tramite->min_personas_reserva} personas).");
        }

        // Validación de Excel vs Cantidad de Personas (antes de abrir transacción de BD)
        if ($request->boolean('es_grupal') || $usaCupoPorDelegacion) {
            if ($request->hasFile('archivo_integrantes')) {
                $data = Excel::toArray([], $request->file('archivo_integrantes'));
                if (isset($data[0]) && count($data[0]) > 1) {
                    $filas_excel = count($data[0]) - 1;
                    if ($filas_excel != $solicitadas) {
                        return back()->withInput()->with('error', "La cantidad de integrantes en el Excel ({$filas_excel}) no coincide con la cantidad declarada ({$solicitadas}). Por favor, corrija el archivo o el número de personas.");
                    }
                }
            }
        }

        $diasMap = [
            1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'
        ];
        $diaSemana = $diasMap[$fecha->dayOfWeekIso];

        try {
            // Transacción atómica con bloqueo pesimista (FOR UPDATE) sobre la fila padre turnos_horarios
            // Garantiza cero sobreventa ante pedidos simultáneos incluso cuando hay 0 reservas previas.
            $turno_reserva = DB::transaction(function () use (
                $turno_horario_id,
                $dependencia_tramite_id,
                $dependencia_tramite,
                $usaCupoPorDelegacion,
                $fecha,
                $fecha_hora,
                $turno_hora,
                $diaSemana,
                $solicitadas,
                $dniNormalizado,
                $input,
                $request
            ) {
                $turno_horario = \App\Models\Turnos_Horarios::with('turno_tramite.tramite.dependencia')
                    ->where('id', $turno_horario_id)
                    ->lockForUpdate()
                    ->first();

                if (!$turno_horario || !$turno_horario->activo || !$turno_horario->$diaSemana) {
                    throw new \RuntimeException('El horario seleccionado ya no se encuentra habilitado para ese día.');
                }

                $turno_tramite = $turno_horario->turno_tramite;
                if (!$turno_tramite || !$turno_tramite->activo || $fecha->lt($turno_tramite->fecha_desde->copy()->startOfDay()) || $fecha->gt($turno_tramite->fecha_hasta->copy()->startOfDay())) {
                    throw new \RuntimeException('La fecha seleccionada se encuentra fuera del período habilitado para este trámite.');
                }

                // Evitar duplicación del mismo DNI en el mismo día y horario
                $duplicados = Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario_id)
                    ->whereDate('fecha', $fecha)
                    ->where('hora', $turno_hora)
                    ->where('estado_id', '!=', 4)
                    ->pluck('dni');

                foreach ($duplicados as $dniExistente) {
                    if (Turnos_Dependencias_Reservas::normalizarDni($dniExistente) === $dniNormalizado) {
                        throw new \RuntimeException('Ya existe una reserva activa registrada con este DNI para el mismo día y horario.');
                    }
                }

                // Control de cupo atómico diferenciado por modalidad del trámite
                if ($usaCupoPorDelegacion) {
                    $reservas_count = Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario_id)
                        ->whereDate('fecha', $fecha)
                        ->where('hora', $turno_hora)
                        ->where('estado_id', '!=', 4)
                        ->count();

                    if ($reservas_count >= $turno_horario->cantidad_turnos) {
                        throw new \RuntimeException('El horario seleccionado ya no posee cupos disponibles para una delegación.');
                    }
                } else {
                    $reservas_sum = (int) Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario_id)
                        ->whereDate('fecha', $fecha)
                        ->where('hora', $turno_hora)
                        ->where('estado_id', '!=', 4)
                        ->sum('cantidad_personas');

                    if (($reservas_sum + $solicitadas) > $turno_horario->cantidad_turnos) {
                        $disponibles = max(0, $turno_horario->cantidad_turnos - $reservas_sum);
                        throw new \RuntimeException("El turno seleccionado no tiene cupo suficiente. Lugares disponibles: {$disponibles}. Usted solicitó: {$solicitadas}.");
                    }
                }

                $dependencia_codigo = optional(optional(optional($turno_tramite)->tramite)->dependencia)->codigo ?? 'OBS';
                $codigoGenerado = Turnos_Dependencias_Reservas::generarCodigoUnico($dependencia_codigo);

                $aux_input = [
                    'codigo' => $codigoGenerado,
                    'fecha_hora' => $fecha_hora,
                    'fecha' => $fecha,
                    'hora' => $turno_hora,
                    'nombre_apellido' => trim($input['nombre_apellido']),
                    'dni' => $dniNormalizado,
                    'celular' => trim($input['celular']),
                    'email' => trim($input['email']),
                    'es_grupal' => $request->boolean('es_grupal') || $usaCupoPorDelegacion || $solicitadas > 1,
                    'cantidad_personas' => $solicitadas,
                    'nombre_institucion' => $request->input('nombre_institucion'),
                    'cargo_responsable' => $request->input('cargo_responsable'),
                    'nivel_institucion' => $request->input('nivel_institucion'),
                    'cantidad_acompanantes' => (int) $request->input('cantidad_acompanantes', 0),
                    'curso_comision' => $request->input('curso_comision'),
                    'turno_horario_id' => $turno_horario_id,
                    'dependencia_tramite_id' => $dependencia_tramite_id,
                    'estado_id' => 1, // Pendiente de confirmación (cupo ya reservado)
                    'activo' => 1
                ];

                $reserva = Turnos_Dependencias_Reservas::create($aux_input);

                if ($request->hasFile('archivo_integrantes')) {
                    Excel::import(new IntegrantesImport($reserva->id), $request->file('archivo_integrantes'));
                }

                return $reserva;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        // Autorizar la reserva recién creada en la sesión del visitante
        $this->autorizarReservaEnSesion($request, $turno_reserva);

        // Enviar correo de solicitud registrada (fuera de la transacción para no retener el lock)
        if (!empty($turno_reserva->email)) {
            try {
                $turno_reserva->load('turno_horario.turno_tramite.tramite.dependencia');
                Mail::to($turno_reserva->email)->send(new \App\Mail\TurnoSolicitado($turno_reserva));
            } catch (\Exception $e) {
                Log::error("Error al enviar mail de solicitud registrada para turno {$turno_reserva->codigo}: " . $e->getMessage());
            }
        }

        $request->session()->forget('dependencia_id');
        $request->session()->forget('dependencia_tramite_id');
        $request->session()->forget('turno_tramite_id');
        $request->session()->forget('turno_fecha');
        $request->session()->forget('turno_hora');
        $request->session()->forget('turno_reserva_busqueda');

        return redirect()->route('tramite.confirmacion', ['id' => $turno_reserva->id]);
    }

    public function confirmacion(Request $request, $id)
    {
        $turno_reserva = Turnos_Dependencias_Reservas::with([
            'turno_tramite.tramite.dependencia',
            'turno_horario.turno_tramite.tramite.dependencia',
            'integrantes'
        ])->find($id);

        if (!$turno_reserva || !$this->puedeAccederReserva($request, $turno_reserva)) {
            return redirect()->route('turnos')->with('error', 'Por seguridad, para ver los detalles de tu turno consultalo abajo ingresando tu código de reserva y tu DNI.');
        }

        return view('frontend.form-turno-paso4')->with(compact('turno_reserva'));
    }

    public function print(Request $request, $id)
    {
        $turno_reserva = Turnos_Dependencias_Reservas::with([
            'turno_tramite.tramite.dependencia',
            'turno_horario.turno_tramite.tramite.dependencia',
            'integrantes'
        ])->findOrFail($id);

        if (!$this->puedeAccederReserva($request, $turno_reserva)) {
            abort(403, 'No tiene autorización para descargar este comprobante. Consulte su reserva ingresando código y DNI.');
        }

        $turno_reserva->fecha_hora_text = $turno_reserva->fecha_hora
            ? $turno_reserva->fecha_hora->format('d/m/Y H:i')
            : '';

        $html = view('htmltopdf.turno_comprobante')
            ->with('turno_reserva', $turno_reserva)->render();
       
        $pdf = \PDF::loadHTML($html)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->setOption('margin-top', '10mm')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-left', '12mm')
            ->setOption('margin-right', '12mm');

        return $pdf->download('comprobante_'.$turno_reserva->codigo.'.pdf'); 
    }

    protected function buscar(SearchTurno $request)
    {
        $codigo = $request->input('codigo_turno');
        $dni = $request->input('dni_turno');

        $reserva = Turnos_Dependencias_Reservas::buscarPorCodigoYDni($codigo, $dni);

        if (!$reserva) {
            return back()->withInput()->with('error', 'No se encontró ninguna reserva que coincida con ese código y DNI. Revisá ambos datos e intentá nuevamente.');
        }

        // Autorizar esta reserva en la sesión tras verificar el doble factor (Código + DNI)
        $this->autorizarReservaEnSesion($request, $reserva);

        return back()->with('turno_reserva_busqueda', $reserva);
    }

    public function loadHorarios(Request $request)
    {
        $input = $request->all();

        $turno_tramite = Turnos_Tramites::with('turnosHorarios')->find($input['id']);

        if (!$turno_tramite) {
            return 'Error: Configuración de turnos no encontrada.';
        }

        $turno_fecha = Carbon::createFromFormat('d/m/Y', $input['turno_fecha']);

        $dependencia_tramite = Dependencia_Tramite::find($turno_tramite->dependencia_tramite_id);
        $isInstitucional = $dependencia_tramite && $dependencia_tramite->usaCupoPorDelegacion();

        $reservas = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $turno_tramite->dependencia_tramite_id)
            ->whereDate('fecha', $turno_fecha)
            ->where('estado_id', '!=', 4)
            ->select('turno_horario_id', 'hora', DB::raw('sum(cantidad_personas) as total_personas'), DB::raw('count(*) as total_reservas'))
            ->groupBy('turno_horario_id', 'hora')
            ->get();
        
        Log::info('Reservas: ', $reservas->toArray());

        $aHorarios = [];
        $now = Carbon::now();
        $isToday = $turno_fecha->isToday();

        $diasMap = [
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
            7 => 'domingo'
        ];
        $diaSemana = $diasMap[$turno_fecha->dayOfWeekIso];

        foreach ($turno_tramite->turnosHorarios as $horario) {
            if (!$horario->activo || !$horario->$diaSemana || (int) $horario->duracion_minutos <= 0) {
                continue;
            }

            $tStart = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_inicio);
            $tEnd = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_fin);
            $duration = (int) $horario->duracion_minutos;

            $tCurrent = $tStart->copy();

            while ($tCurrent < $tEnd) {
                $slot = $tCurrent->format('H:i:s');
                
                $reservas_for_slot = $reservas->where('turno_horario_id', $horario->id)->where('hora', $slot)->first();
                $reservas_count = $reservas_for_slot ? ($isInstitucional ? $reservas_for_slot->total_reservas : $reservas_for_slot->total_personas) : 0;

                $isPastSlotForToday = $isToday && $tCurrent->isPast();

                Log::info('Horario: ', ['id' => $horario->id, 'slot' => $slot, 'reservas_count' => $reservas_count, 'cantidad_turnos' => $horario->cantidad_turnos]);

                if (!$isPastSlotForToday && $reservas_count < $horario->cantidad_turnos) {
                    $available_slots = $horario->cantidad_turnos - $reservas_count;
                    $aHorarios[] = [
                        'hora' => $slot,
                        'id' => $horario->id,
                        'available' => $available_slots
                    ];
                }

                $tCurrent->addMinutes($duration);
            }
        }
        
        sort($aHorarios);
        $aHorarios = array_unique($aHorarios, SORT_REGULAR);

        if (empty($aHorarios)) {
            $html = '<div class="text-center py-4 px-3">'
                . '<i class="fas fa-calendar-times fa-2x text-muted mb-2 d-block"></i>'
                . '<p class="font-weight-bold text-dark mb-1">No quedan horarios disponibles para este día</p>'
                . '<small class="text-muted">Por favor elija otra fecha pintada de azul en el calendario.</small>'
                . '</div>';
        } else {
            $html = '<div class="slots-grid w-100 text-left">';
            foreach ($aHorarios as $idx => $horario) {
                $horaCorta = substr($horario['hora'], 0, 5);
                $val = $horario['hora'] . '|' . $horario['id'];
                $slotId = 'slot_hora_' . $idx;
                $cupoTexto = $isInstitucional
                    ? ($horario['available'] == 1 ? '1 cupo disponible (delegación)' : $horario['available'] . ' cupos disponibles')
                    : ($horario['available'] == 1 ? '1 lugar disponible' : $horario['available'] . ' lugares disponibles');

                $html .= '<label class="slot-option" for="' . $slotId . '">'
                    . '<input type="radio" id="' . $slotId . '" name="turno_hora" value="' . e($val) . '" data-hora-corta="' . e($horaCorta) . '" required>'
                    . '<div class="slot-card">'
                    . '<div class="slot-card-left">'
                    . '<span class="slot-time"><i class="far fa-clock mr-1"></i> ' . e($horaCorta) . ' hs</span>'
                    . '<span class="slot-seats">' . e($cupoTexto) . '</span>'
                    . '</div>'
                    . '<span class="slot-check"><i class="fas fa-check-circle"></i></span>'
                    . '</div>'
                    . '</label>';
            }
            $html .= '</div>';
        }

        return $html;
    }

    public function getDirecciones()
    {
        //Se obtiene las dependencias que tienen emiten turnos
        $dependencias = Dependencia::getDependenciasConTurnos();

        $dependencias = (array) $dependencias;
        header('Content-Type: application/json');
        return json_encode($dependencias);
    }
    
    private function checkAvailability(int $dependencia_tramite_id): bool
    {
        $has_turns = false;
        $today = Carbon::today();
        $dependencia_tramite = Dependencia_Tramite::find($dependencia_tramite_id);
        $isInstitucional = $dependencia_tramite && $dependencia_tramite->usaCupoPorDelegacion();

        $turno_tramite = Turnos_Tramites::where('dependencia_tramite_id', $dependencia_tramite_id)
            ->where('activo', true)
            ->where('fecha_hasta', '>=', $today)
            ->orderByDesc('id')
            ->first();

        if ($turno_tramite) {
            $feriados = Feriado::where('activo', 1)
                ->where('fecha', '>=', $today)
                ->get()
                ->map(function ($feriado) {
                    return Carbon::createFromFormat('d/m/Y', $feriado->fecha)->format('Y-m-d');
                })->toArray();
            
            $fecha_desde = $turno_tramite->fecha_desde;
            if ($today->gt($fecha_desde)) {
                $fecha_desde = $today;
            }
            $fecha_hasta = $turno_tramite->fecha_hasta;

            $current_date = $fecha_desde->copy();

            $reservas_all = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $dependencia_tramite_id)
                ->whereDate('fecha', '>=', $fecha_desde)
                ->whereDate('fecha', '<=', $fecha_hasta)
                ->where('estado_id', '!=', 4)
                ->select(DB::raw('DATE(fecha) as fecha_date'), 'turno_horario_id', 'hora', DB::raw('sum(cantidad_personas) as total_personas'), DB::raw('count(*) as total_reservas'))
                ->groupBy('fecha_date', 'turno_horario_id', 'hora')
                ->get()
                ->groupBy('fecha_date');

            while ($current_date->lte($fecha_hasta) && !$has_turns) {
                if (in_array($current_date->format('Y-m-d'), $feriados)) {
                    $current_date->addDay();
                    continue;
                }

                $reservas_del_dia = $reservas_all->get($current_date->format('Y-m-d'));
                
                $reservas_por_horario_y_hora = [];
                if ($reservas_del_dia) {
                    foreach ($reservas_del_dia as $reserva) {
                        if (!isset($reservas_por_horario_y_hora[$reserva->turno_horario_id])) {
                            $reservas_por_horario_y_hora[$reserva->turno_horario_id] = [];
                        }
                        $reservas_por_horario_y_hora[$reserva->turno_horario_id][$reserva->hora] = $isInstitucional ? $reserva->total_reservas : $reserva->total_personas;
                    }
                }
                
                $isToday = $current_date->isToday();

                $diasMap = [
                    1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'
                ];
                $diaSemana = $diasMap[$current_date->dayOfWeekIso];

                foreach ($turno_tramite->turnosHorarios as $horario) {
                    if (!$horario->activo || !$horario->$diaSemana || (int) $horario->duracion_minutos <= 0) {
                        continue;
                    }

                    $tStart = $current_date->copy()->setTimeFromTimeString($horario->hora_inicio);
                    $tEnd = $current_date->copy()->setTimeFromTimeString($horario->hora_fin);
                    $duration = (int) $horario->duracion_minutos;

                    $tCurrent = $tStart->copy();

                    while ($tCurrent->lt($tEnd)) {
                        $slot = $tCurrent->format('H:i:s');
                        $reservas_count = $reservas_por_horario_y_hora[$horario->id][$slot] ?? 0;
                        
                        $isPastSlotForToday = $isToday && $tCurrent->isPast();

                        if (!$isPastSlotForToday && $reservas_count < $horario->cantidad_turnos) {
                            $has_turns = true;
                            break 3;
                        }
                        $tCurrent->addMinutes($duration);
                    }
                }
                $current_date->addDay();
            }
        }
        return $has_turns;
    }

    public function getTramites($id)
    {
        $dependencias_tramites = Dependencia_Tramite::where('dependencia_id', $id)->get()->toArray();

        foreach ($dependencias_tramites as &$tramite) {
            $tramite['has_turns'] = $this->checkAvailability($tramite['id']);
        }

        header('Content-Type: application/json');
        return json_encode($dependencias_tramites);
    }

    public function getDisabledDates(Request $request)
    {
        $input = $request->all();
        $turno_tramite = Turnos_Tramites::with('turnosHorarios')->find($input['id']);

        if (!$turno_tramite) {
            return response()->json([]);
        }

        $dependencia_tramite = Dependencia_Tramite::find($turno_tramite->dependencia_tramite_id);
        $isInstitucional = $dependencia_tramite && $dependencia_tramite->usaCupoPorDelegacion();

        $fecha_desde = $turno_tramite->fecha_desde;
        if (Carbon::today() > $fecha_desde) {
            $fecha_desde = Carbon::today();
        }
        $fecha_hasta = $turno_tramite->fecha_hasta;

        $disabled_dates = [];
        $current_date = $fecha_desde->copy();

        $reservas = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $turno_tramite->dependencia_tramite_id)
            ->whereDate('fecha', '>=', $fecha_desde)
            ->whereDate('fecha', '<=', $fecha_hasta)
            ->where('estado_id', '!=', 4)
            ->select(DB::raw('DATE(fecha) as fecha_date'), 'turno_horario_id', 'hora', DB::raw('sum(cantidad_personas) as total_personas'), DB::raw('count(*) as total_reservas'))
            ->groupBy('fecha_date', 'turno_horario_id', 'hora')
            ->get()
            ->groupBy('fecha_date');

        while ($current_date <= $fecha_hasta) {
            $aHorarios = [];
            $turno_fecha = $current_date;
            $isToday = $turno_fecha->isToday();
            $reservas_del_dia = $reservas->get($turno_fecha->format('Y-m-d'));

            $reservas_por_horario_y_hora = [];
            if ($reservas_del_dia) {
                foreach ($reservas_del_dia as $res) {
                    $reservas_por_horario_y_hora[$res->turno_horario_id][$res->hora] = $isInstitucional
                        ? (int) $res->total_reservas
                        : (int) $res->total_personas;
                }
            }

            $diasMap = [
                1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'
            ];
            $diaSemana = $diasMap[$turno_fecha->dayOfWeekIso];

            foreach ($turno_tramite->turnosHorarios as $horario) {
                if (!$horario->activo || !$horario->$diaSemana || (int) $horario->duracion_minutos <= 0) {
                    continue;
                }

                $tStart = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_inicio);
                $tEnd = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_fin);
                $duration = (int) $horario->duracion_minutos;

                $tCurrent = $tStart->copy();

                while ($tCurrent < $tEnd) {
                    $slot = $tCurrent->format('H:i:s');
                    $reservas_count = $reservas_por_horario_y_hora[$horario->id][$slot] ?? 0;
                    $isPastSlotForToday = $isToday && $tCurrent->isPast();

                    if (!$isPastSlotForToday && $reservas_count < $horario->cantidad_turnos) {
                        $aHorarios[] = $slot;
                    }

                    $tCurrent->addMinutes($duration);
                }
            }

            if (empty($aHorarios)) {
                $disabled_dates[] = $current_date->format('d/m/Y');
            }

            $current_date->addDay();
        }

        return response()->json($disabled_dates);
    }

    public function misTurnos()
    {
        $user = auth()->user();
        $reservas = Turnos_Dependencias_Reservas::with(['turno_horario.turno_tramite.tramite.dependencia'])
            ->where('email', $user->email)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return view('frontend.mis-turnos', compact('reservas'));
    }

    public function cargarIntegrantesForm(Request $request, $codigo)
    {
        $reserva = Turnos_Dependencias_Reservas::with(['turno_horario.turno_tramite.tramite.dependencia', 'integrantes'])
            ->where('codigo', $codigo)
            ->firstOrFail();

        if (!$this->puedeAccederReserva($request, $reserva)) {
            return redirect()->route('turnos')->with('error', 'Por seguridad, ingresá tu código de reserva y tu DNI en la consulta para gestionar la nómina de integrantes.');
        }

        return view('frontend.cargar-integrantes', compact('reserva'));
    }

    public function guardarIntegrantes(Request $request, $codigo)
    {
        $reserva = Turnos_Dependencias_Reservas::where('codigo', $codigo)->firstOrFail();

        if (!$this->puedeAccederReserva($request, $reserva)) {
            abort(403, 'No tiene autorización para modificar la nómina de esta reserva.');
        }

        if ($reserva->estado_id == 4) {
            return back()->with('error', 'No se puede modificar la nómina de una reserva cancelada.');
        }

        if ($reserva->fecha_hora && $reserva->fecha_hora->isPast()) {
            return back()->with('error', 'No se puede modificar la nómina de una visita ya transcurrida.');
        }

        if ($request->hasFile('archivo_integrantes')) {
            $request->validate([
                'archivo_integrantes' => 'required|file|mimes:xls,xlsx,csv'
            ]);

            \App\Models\ReservaIntegrante::where('reserva_id', $reserva->id)->delete();
            Excel::import(new IntegrantesImport($reserva->id), $request->file('archivo_integrantes'));

            return back()->with('success', 'Nómina de integrantes importada correctamente desde el archivo Excel.');
        }

        if ($request->has('integrantes_manuales')) {
            $integrantes = $request->input('integrantes_manuales', []);
            \App\Models\ReservaIntegrante::where('reserva_id', $reserva->id)->delete();

            foreach ($integrantes as $item) {
                if (!empty($item['nombre']) || !empty($item['apellido']) || !empty($item['dni'])) {
                    \App\Models\ReservaIntegrante::create([
                        'reserva_id' => $reserva->id,
                        'nombre' => $item['nombre'] ?? '',
                        'apellido' => $item['apellido'] ?? '',
                        'dni' => $item['dni'] ?? ''
                    ]);
                }
            }

            return back()->with('success', 'Lista de integrantes actualizada correctamente.');
        }

        return back()->with('error', 'Seleccione un archivo Excel o cargue al menos un integrante manualmente.');
    }

    public function cancelarReserva(Request $request, $codigo)
    {
        $request->validate([
            'motivo_cancelacion' => 'required|string|min:5|max:1000',
            'dni_cancelacion' => 'nullable|string|max:20'
        ], [
            'motivo_cancelacion.required' => 'Debe ingresar el motivo de la cancelación.',
            'motivo_cancelacion.min' => 'El motivo de cancelación debe tener al menos 5 caracteres.'
        ]);

        $reserva = Turnos_Dependencias_Reservas::where('codigo', $codigo)->firstOrFail();

        if (!$this->puedeAccederReserva($request, $reserva)) {
            abort(403, 'No tiene autorización para cancelar esta reserva. Debe verificar primero su código y DNI.');
        }

        // Para visitantes públicos (no autenticados como titular ni admin/operador), exigir confirmación explícita del DNI
        if (!$this->esAdminOOperador() && !$this->esTitularAutenticado($reserva)) {
            $dniEnviado = Turnos_Dependencias_Reservas::normalizarDni($request->input('dni_cancelacion'));
            if ($dniEnviado === '' || $dniEnviado !== Turnos_Dependencias_Reservas::normalizarDni($reserva->dni)) {
                return back()->with('error', 'Debe confirmar el número de DNI del titular para cancelar esta reserva.');
            }
        }

        if ($reserva->estado_id == 4) {
            return back()->with('error', 'Esta reserva ya se encuentra cancelada.');
        }

        if ($reserva->fecha_hora && $reserva->fecha_hora->isPast()) {
            return back()->with('error', 'No es posible cancelar un turno cuya fecha y horario ya pasaron.');
        }

        $reserva->estado_id = 4; // Cancelado
        $reserva->activo = 0;
        $reserva->motivo_cancelacion = $request->input('motivo_cancelacion');
        $reserva->save();

        if (!empty($reserva->email)) {
            try {
                Mail::to($reserva->email)->send(new \App\Mail\TurnoCancelado($reserva));
            } catch (\Exception $e) {
                Log::error("Error al enviar mail de cancelación para turno {$reserva->codigo}: " . $e->getMessage());
            }
        }

        return back()->with('success', "El turno {$reserva->codigo} ha sido cancelado exitosamente y el cupo ha sido liberado.");
    }
}
