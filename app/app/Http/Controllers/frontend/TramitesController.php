<?php

namespace App\Http\Controllers\Frontend;

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

        return view('frontend.form-turno-paso2')->with(compact('turno_dependencia', 'turno_fecha', 'turno_hora', 'fecha_desde', 'feriados','feriados_text'));
    }

    public function paso3(Request $request)
    {
        try { 
            $input = $request->all();

            $request->session()->put('turno_fecha', $input['turno_fecha']);
            $request->session()->put('turno_hora', $input['turno_hora']);

            $dependencia_id = $request->session()->get('dependencia_id');
            $turno_fecha = $request->session()->get('turno_fecha');
            $turno_hora = $request->session()->get('turno_hora');

            $dependencia = Dependencia::find($dependencia_id)->toArray();

            $usuario = Auth::user();

            return view('frontend.form-turno-paso3')->with(compact('dependencia', 'turno_fecha', 'turno_hora', 'usuario'));
        } catch (\Exception $e) {
            return redirect(route('tramite.index'));
        }
    }

    public function guardar(StoreTurno $request)
    {
        $input = $request->all();

        $values = $request->session()->all();

        list($turno_hora, $turno_horario_id) = explode('|', $request->session()->get('turno_hora'));

        // START of the check
        $turno_horario = \App\Models\Turnos_Horarios::find($turno_horario_id);
        $fecha = Carbon::createFromFormat('d/m/Y', $request->session()->get('turno_fecha'));

        $reservas_count = Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario_id)
            ->whereDate('fecha', $fecha)
            ->where('hora', $turno_hora)
            ->count();

        if ($reservas_count >= $turno_horario->cantidad_turnos) {
            return redirect()->route('tramite.index')->with('error', 'El turno seleccionado ya no está disponible. Por favor, elija otro horario.');
        }
        // END of the check

        $aux_input = [
            'fecha_hora' => Carbon::createFromFormat('d/m/Y H:i:s', $request->session()->get('turno_fecha').' '.$turno_hora),
            'fecha' => Carbon::createFromFormat('d/m/Y', $request->session()->get('turno_fecha')),
            'hora' => $turno_hora,
            'nombre_apellido' => $input['nombre_apellido'],
            'dni' => $input['dni'],
            'celular' => $input['celular'],
            'email' => $input['email'],
            'turno_horario_id' => $turno_horario_id,
            'dependencia_tramite_id' => $request->session()->get('dependencia_tramite_id'),
            'estado_id' => 1,
            'activo' => 1
        ];

        $turno_reserva = Turnos_Dependencias_Reservas::create($aux_input);

        $dependencia_codigo = $turno_reserva->turno_horario->turno_tramite->tramite->dependencia->codigo;
        $turno_reserva->codigo = $dependencia_codigo.str_pad($turno_reserva->id, 6, "0", STR_PAD_LEFT);
        $turno_reserva->save();

        $request->session()->forget('dependencia_id');
        $request->session()->forget('dependencia_tramite_id');
        $request->session()->forget('turno_tramite_id');
        $request->session()->forget('turno_fecha');
        $request->session()->forget('turno_hora');
        
        $request->session()->forget('turno_reserva_busqueda'); // Forget the search result from session
        return redirect()->route('tramite.confirmacion', ['id' => $turno_reserva->id]);
    }

    public function confirmacion($id)
    {
        $turno_reserva = Turnos_Dependencias_Reservas::with('turno_tramite.tramite.dependencia')->find($id);
        return view('frontend.form-turno-paso4')->with(compact('turno_reserva'));
    }

    public function print($id)
    {
        $turno_reserva = Turnos_Dependencias_Reservas::with('turno_tramite.tramite.dependencia')->find($id);

        $turno_reserva->fecha_hora_text = $turno_reserva->fecha_hora->format('d/m/Y h:i');

        $html = view('htmltopdf.turno_comprobante')
            ->with('turno_reserva', $turno_reserva)->render();
       
        $pdf = \PDF::loadHTML($html);
        return $pdf->download('comprobante_'.$turno_reserva->codigo.'.pdf'); 
    }

    protected function buscar(SearchTurno $request)
    {
        $input = $request->all();
        $query = Turnos_Dependencias_Reservas::with('turno_horario.turno_tramite.tramite.dependencia');

        if (!empty($input['codigo_turno'])) {
            $query->where('codigo', $input['codigo_turno']);
        }

        if (!empty($input['dni_turno'])) {
            $query->where('dni', $input['dni_turno']);
        }

        $turno_reserva_busqueda = $query->first();

        return back()->with('turno_reserva_busqueda', $turno_reserva_busqueda);
    }

    public function loadHorarios(Request $request)
    {
        $input = $request->all();

        $turno_tramite = Turnos_Tramites::with('turnosHorarios')->find($input['id']);

        if (!$turno_tramite) {
            return 'Error: Configuración de turnos no encontrada.';
        }

        $turno_fecha = Carbon::createFromFormat('d/m/Y', $input['turno_fecha']);

        $reservas = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $turno_tramite->dependencia_tramite_id)
            ->whereDate('fecha', $turno_fecha)
            ->select('turno_horario_id', 'hora', DB::raw('count(*) as total'))
            ->groupBy('turno_horario_id', 'hora')
            ->get();
        
        Log::info('Reservas: ', $reservas->toArray());

        $aHorarios = [];
        $now = Carbon::now();
        $isToday = $turno_fecha->isToday();

        foreach ($turno_tramite->turnosHorarios as $horario) {
            if (!$horario->activo) {
                continue;
            }

            $tStart = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_inicio);
            $tEnd = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_fin);
            $duration = $horario->duracion_minutos;

            $tCurrent = $tStart->copy();

            while ($tCurrent < $tEnd) {
                $slot = $tCurrent->format('H:i:s');
                
                $reservas_for_slot = $reservas->where('turno_horario_id', $horario->id)->where('hora', $slot)->first();
                $reservas_count = $reservas_for_slot ? $reservas_for_slot->total : 0;

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
            $html = 'Sin turnos. Por favor seleccione otro día.';
        } else {
            $html = '<select name="turno_hora" size="30" class="select list-group overflow-auto text-center" style="max-height: 267px; margin-bottom: 10px; width: 100%; border: 0px;" required="true">';

            foreach ($aHorarios as $horario) {
                $html = $html.'<option class="list-group-item" value="'.$horario['hora'].'|'.$horario['id'].'">'.$horario['hora'].' ('.$horario['available'].' disponibles)</option>';
            }
            $html = $html.'</select>';
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
                ->select(DB::raw('DATE(fecha) as fecha_date'), 'turno_horario_id', 'hora', DB::raw('count(*) as total'))
                ->groupBy('fecha_date', 'turno_horario_id', 'hora')
                ->get()
                ->groupBy('fecha_date');

            while ($current_date->lte($fecha_hasta) && !$has_turns) {
                if ($current_date->isWeekend() || in_array($current_date->format('Y-m-d'), $feriados)) {
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
                        $reservas_por_horario_y_hora[$reserva->turno_horario_id][$reserva->hora] = $reserva->total;
                    }
                }
                
                $isToday = $current_date->isToday();

                foreach ($turno_tramite->turnosHorarios as $horario) {
                    if (!$horario->activo) {
                        continue;
                    }

                    $tStart = $current_date->copy()->setTimeFromTimeString($horario->hora_inicio);
                    $tEnd = $current_date->copy()->setTimeFromTimeString($horario->hora_fin);
                    $duration = $horario->duracion_minutos;

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
            ->select(DB::raw('DATE(fecha) as fecha_date'), 'hora', DB::raw('count(*) as total'))
            ->groupBy('fecha_date', 'hora')
            ->get()
            ->groupBy('fecha_date');


        while ($current_date <= $fecha_hasta) {
            $aHorarios = [];
            $turno_fecha = $current_date;
            $reservas_del_dia = $reservas->get($turno_fecha->format('Y-m-d'));

            $reservas_por_hora = [];
            if ($reservas_del_dia) {
                $reservas_por_hora = $reservas_del_dia->pluck('total', 'hora')->toArray();
            }


            foreach ($turno_tramite->turnosHorarios as $horario) {
                if (!$horario->activo) {
                    continue;
                }


                $tStart = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_inicio);
                $tEnd = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_fin);
                $duration = $horario->duracion_minutos;

                $tCurrent = $tStart->copy();

                while ($tCurrent < $tEnd) {
                    $slot = $tCurrent->format('H:i');
                    $reservas_count = $reservas_por_hora[$slot] ?? 0;


                    if ($reservas_count < $horario->cantidad_turnos) {
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
}
