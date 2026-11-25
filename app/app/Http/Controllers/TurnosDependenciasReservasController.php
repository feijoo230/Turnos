<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tramite;
use App\Models\Turnos_Dependencias;
use App\Models\Dependencia;
use App\Models\Usuariodependencia;
use App\Models\Turnos_Dependencias_Reservas;
use App\Http\Requests\BusquedaTurno;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TurnosReservadosExport;

class TurnosDependenciasReservasController extends Controller
{
    public function __construct()
    {

    }

    public function export() 
    {
        return Excel::download(new TurnosReservadosExport, 'turnos_reservados.xlsx');
    }

    public function show($id)
    {
        $reserva = Turnos_Dependencias_Reservas::with(['turno_tramite.tramite.dependencia', 'integrantes'])->find($id);

        if (!empty($reserva)) {
            return view('turnosdependenciasreservas.show')->with(compact('reserva'));
        } else {
            return redirect(route('turnosdependenciasreservas.index'))->with('error', 'Error al visualizar reserva');
        }
    }

    public function destroy($id)
    {
        try {
            $reserva = Turnos_Dependencias_Reservas::find($id);

            if (empty($reserva)) {
                // Devuelve un error 404 si no se encuentra la reserva
                return response()->json(['message' => 'Reserva no encontrada'], 404);
            }

            $reserva->delete();

            // Devuelve una respuesta JSON de éxito
            return response()->json(['message' => 'Reserva eliminada con éxito']);

        } catch (\Exception $e) {
            // Devuelve un error 500 si algo más falla
            return response()->json(['message' => 'Error al eliminar la reserva: ' . $e->getMessage()], 500);
        }
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids)) {
            Turnos_Dependencias_Reservas::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Reservas eliminadas correctamente.']);
        }
        return response()->json(['success' => false, 'message' => 'No se seleccionaron reservas.'], 400);
    }

    public function update(Request $request, $id)
    {
        $reserva = Turnos_Dependencias_Reservas::find($id);

        if (empty($reserva)) {
            return redirect(route('turnosdependenciasreservas.index'))->with('error', 'Reserva no encontrada');
        }

        $input = $request->all();

        $reserva->update($input);

        return redirect(route('turnosdependenciasreservas.index'))->with('success', 'Reserva actualizada con éxito');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $reserva = Turnos_Dependencias_Reservas::with('turno_horario.turno_tramite.tramite')->find($id);

        if (empty($reserva)) {
            return back()->with('error', 'Reserva no encontrada');
        }

        $estadoAnterior = (int) $reserva->estado_id;
        $nuevoEstado = (int) $request->input('estado_id', 1);

        try {
            DB::transaction(function () use ($reserva, $estadoAnterior, $nuevoEstado, $request) {
                // Si se reactiva una reserva previamente cancelada, verificar cupo con bloqueo pesimista
                if ($estadoAnterior === 4 && $nuevoEstado !== 4 && $reserva->turno_horario_id) {
                    $turno_horario = \App\Models\Turnos_Horarios::where('id', $reserva->turno_horario_id)
                        ->lockForUpdate()
                        ->first();

                    if ($turno_horario) {
                        $tramite = optional(optional($turno_horario->turno_tramite)->tramite);
                        $usaCupoPorDelegacion = $tramite && method_exists($tramite, 'usaCupoPorDelegacion')
                            ? $tramite->usaCupoPorDelegacion()
                            : false;

                        if ($usaCupoPorDelegacion) {
                            $ocupados = Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario->id)
                                ->whereDate('fecha', $reserva->fecha)
                                ->where('hora', $reserva->hora)
                                ->where('estado_id', '!=', 4)
                                ->count();

                            if ($ocupados >= $turno_horario->cantidad_turnos) {
                                throw new \RuntimeException('No se puede reactivar la reserva porque el cupo de ese horario ya está completo.');
                            }
                        } else {
                            $ocupados = (int) Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario->id)
                                ->whereDate('fecha', $reserva->fecha)
                                ->where('hora', $reserva->hora)
                                ->where('estado_id', '!=', 4)
                                ->sum('cantidad_personas');

                            $solicitadas = (int) ($reserva->cantidad_personas ?: 1);
                            if (($ocupados + $solicitadas) > $turno_horario->cantidad_turnos) {
                                $disponibles = max(0, $turno_horario->cantidad_turnos - $ocupados);
                                throw new \RuntimeException("No hay cupo suficiente para reactivar esta reserva (disponibles: {$disponibles}, requeridos: {$solicitadas}).");
                            }
                        }
                    }
                }

                $reserva->estado_id = $nuevoEstado;
                $reserva->activo = ($nuevoEstado === 4) ? 0 : 1;
                if ($nuevoEstado === 4 && $request->filled('motivo_cancelacion')) {
                    $reserva->motivo_cancelacion = $request->input('motivo_cancelacion');
                }
                $reserva->save();
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($nuevoEstado === 3 && $estadoAnterior !== 3 && !empty($reserva->email)) {
            try {
                Mail::to($reserva->email)->send(new \App\Mail\TurnoConfirmado($reserva));
            } catch (\Exception $e) {
                Log::error("Error al enviar mail de confirmación para turno {$reserva->codigo}: " . $e->getMessage());
            }
        } elseif ($nuevoEstado === 4 && $estadoAnterior !== 4 && !empty($reserva->email)) {
            try {
                Mail::to($reserva->email)->send(new \App\Mail\TurnoCancelado($reserva));
            } catch (\Exception $e) {
                Log::error("Error al enviar mail de cancelación para turno {$reserva->codigo}: " . $e->getMessage());
            }
        }

        return back()->with('success', 'El estado de la reserva ha sido actualizado con éxito.');
    }

    public function cancelacionMasiva(Request $request)
    {
        $request->validate([
            'fecha_cancelacion' => 'required|string',
            'motivo_cancelacion' => 'required|string|min:5|max:1000',
        ], [
            'fecha_cancelacion.required' => 'Debe ingresar o seleccionar la fecha a cancelar.',
            'motivo_cancelacion.required' => 'Debe indicar el motivo de la cancelación masiva.',
            'motivo_cancelacion.min' => 'El motivo de cancelación debe tener al menos 5 caracteres.'
        ]);

        try {
            if (strpos($request->input('fecha_cancelacion'), '/') !== false) {
                $fecha = Carbon::createFromFormat('d/m/Y', $request->input('fecha_cancelacion'))->format('Y-m-d');
            } else {
                $fecha = Carbon::parse($request->input('fecha_cancelacion'))->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Formato de fecha inválido. Utilice el formato DD/MM/AAAA.');
        }

        $dependencia_id = $request->input('dependencia_id');
        $tramite_id = $request->input('tramite_id');
        $motivo = $request->input('motivo_cancelacion');
        $notificar = $request->has('notificar_email');

        $query = Turnos_Dependencias_Reservas::whereDate('fecha', $fecha)
            ->where('estado_id', '!=', 4);

        if ($tramite_id) {
            $query->where('dependencia_tramite_id', $tramite_id);
        }

        if ($dependencia_id) {
            $query->whereHas('turno_horario.turno_tramite.tramite.dependencia', function($q) use ($dependencia_id) {
                $q->where('id', $dependencia_id);
            });
        }

        $usuario_id = Auth::id();
        $user_dependencias = DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id');
        if ($user_dependencias->isNotEmpty()) {
            $query->whereHas('turno_horario.turno_tramite.tramite.dependencia', function($q) use ($user_dependencias) {
                $q->whereIn('id', $user_dependencias);
            });
        }

        $reservas = $query->get();

        if ($reservas->isEmpty()) {
            return back()->with('error', 'No se encontraron turnos activos reservados para la fecha y filtros seleccionados.');
        }

        $count = 0;
        $emailsSent = 0;

        foreach ($reservas as $reserva) {
            $reserva->estado_id = 4; // Cancelado
            $reserva->activo = 0;
            $reserva->motivo_cancelacion = $motivo;
            $reserva->save();
            $count++;

            if ($notificar && !empty($reserva->email)) {
                try {
                    Mail::to($reserva->email)->send(new \App\Mail\TurnoCancelado($reserva));
                    $emailsSent++;
                } catch (\Exception $e) {
                    Log::error("Error al enviar mail de cancelación masiva para turno {$reserva->codigo}: " . $e->getMessage());
                }
            }
        }

        $fechaFormateada = Carbon::parse($fecha)->format('d/m/Y');
        $msg = "Se han cancelado masivamente {$count} reserva(s) para el día {$fechaFormateada}.";
        if ($notificar) {
            $msg .= " Se enviaron {$emailsSent} notificaciones por correo electrónico.";
        }

        return back()->with('success', $msg);
    }

    public function loadHorariosAdmin(Request $request)
    {
        $dependencia_tramite_id = $request->input('dependencia_tramite_id');
        $fecha_raw = $request->input('fecha');

        if (!$dependencia_tramite_id || !$fecha_raw) {
            return response()->json(['error' => 'Parámetros insuficientes'], 400);
        }

        try {
            if (strpos($fecha_raw, '/') !== false) {
                $fechaCarbon = Carbon::createFromFormat('d/m/Y', $fecha_raw)->startOfDay();
            } else {
                $fechaCarbon = Carbon::parse($fecha_raw)->startOfDay();
            }
            $fecha = $fechaCarbon->format('Y-m-d');
            $diaSemanaNum = $fechaCarbon->dayOfWeekIso;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Formato de fecha inválido'], 400);
        }

        $diasMap = [
            1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'
        ];
        $diaCol = $diasMap[$diaSemanaNum];

        $turno_tramite = \App\Models\Turnos_Tramites::with(['turnosHorarios', 'tramite'])
            ->where('dependencia_tramite_id', $dependencia_tramite_id)
            ->where('activo', true)
            ->whereDate('fecha_desde', '<=', $fecha)
            ->whereDate('fecha_hasta', '>=', $fecha)
            ->orderByDesc('id')
            ->first();

        if (!$turno_tramite) {
            return response()->json([], 200);
        }

        $usaCupoPorDelegacion = $turno_tramite->tramite && $turno_tramite->tramite->usaCupoPorDelegacion();

        $horarios = $turno_tramite->turnosHorarios->filter(function ($h) use ($diaCol) {
            return $h->activo && $h->$diaCol && (int) $h->duracion_minutos > 0;
        });

        $reservas = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $dependencia_tramite_id)
            ->whereDate('fecha', $fecha)
            ->where('estado_id', '!=', 4)
            ->select('turno_horario_id', 'hora', DB::raw('sum(cantidad_personas) as total_personas'), DB::raw('count(*) as total_reservas'))
            ->groupBy('turno_horario_id', 'hora')
            ->get();

        $ocupadosMap = [];
        foreach ($reservas as $res) {
            $ocupadosMap[$res->turno_horario_id][$res->hora] = $usaCupoPorDelegacion
                ? (int) $res->total_reservas
                : (int) $res->total_personas;
        }

        $resultado = [];
        foreach ($horarios as $h) {
            $tStart = $fechaCarbon->copy()->setTimeFromTimeString($h->hora_inicio);
            $tEnd = $fechaCarbon->copy()->setTimeFromTimeString($h->hora_fin);
            $duration = (int) $h->duracion_minutos;

            for ($tCurrent = $tStart->copy(); $tCurrent->lt($tEnd); $tCurrent->addMinutes($duration)) {
                $slot = $tCurrent->format('H:i:s');
                $horaCorta = $tCurrent->format('H:i');
                $ocupados = $ocupadosMap[$h->id][$slot] ?? 0;
                $disponibles = max(0, $h->cantidad_turnos - $ocupados);
                $unidad = $usaCupoPorDelegacion ? 'delegaciones' : 'lugares';

                $resultado[] = [
                    'id' => "{$h->id}|{$slot}",
                    'hora' => $slot,
                    'cantidad_turnos' => $h->cantidad_turnos,
                    'ocupados' => $ocupados,
                    'disponibles' => $disponibles,
                    'label' => "{$horaCorta} hs (Disponibles: {$disponibles} / Capacidad: {$h->cantidad_turnos} {$unidad})"
                ];
            }
        }

        return response()->json($resultado);
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'dependencia_tramite_id' => 'required|integer',
            'fecha_reserva' => 'required|string',
            'turno_horario_id' => 'required|string',
            'nombre_apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'celular' => 'nullable|string|max:100',
            'cantidad_personas' => 'required|integer|min:1',
        ], [
            'dependencia_tramite_id.required' => 'Debe seleccionar un trámite / dependencia.',
            'fecha_reserva.required' => 'Debe seleccionar la fecha de la reserva.',
            'turno_horario_id.required' => 'Debe seleccionar un horario disponible.',
            'nombre_apellido.required' => 'Debe ingresar el nombre del responsable.',
            'dni.required' => 'Debe ingresar el número de DNI.',
        ]);

        try {
            if (strpos($request->input('fecha_reserva'), '/') !== false) {
                $fechaCarbon = Carbon::createFromFormat('d/m/Y', $request->input('fecha_reserva'))->startOfDay();
            } else {
                $fechaCarbon = Carbon::parse($request->input('fecha_reserva'))->startOfDay();
            }
            $fecha = $fechaCarbon->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Formato de fecha inválido.');
        }

        $rawHorario = (string) $request->input('turno_horario_id');
        if (strpos($rawHorario, '|') !== false) {
            list($horarioId, $hora) = explode('|', $rawHorario, 2);
        } else {
            $horarioId = $rawHorario;
            $hora = null;
        }

        $dependencia_tramite = \App\Models\Dependencia_Tramite::find($request->input('dependencia_tramite_id'));
        $usaCupoPorDelegacion = $dependencia_tramite && $dependencia_tramite->usaCupoPorDelegacion();

        try {
            $reserva = DB::transaction(function () use ($horarioId, $hora, $fecha, $fechaCarbon, $usaCupoPorDelegacion, $request) {
                $turno_horario = \App\Models\Turnos_Horarios::with('turno_tramite.tramite.dependencia')
                    ->where('id', (int) $horarioId)
                    ->lockForUpdate()
                    ->first();

                if (!$turno_horario) {
                    throw new \RuntimeException('El horario seleccionado no es válido.');
                }

                $horaFinal = $hora ?: $turno_horario->hora_inicio;
                if (strlen($horaFinal) === 5) {
                    $horaFinal .= ':00';
                }

                $solicitadas = (int) $request->input('cantidad_personas', 1);
                if ($request->boolean('bloquear_cupo_completo')) {
                    $solicitadas = max($solicitadas, $turno_horario->cantidad_turnos);
                }

                // Verificar cupo disponible de forma atómica
                if ($usaCupoPorDelegacion) {
                    $ocupados = Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario->id)
                        ->whereDate('fecha', $fecha)
                        ->where('hora', $horaFinal)
                        ->where('estado_id', '!=', 4)
                        ->count();

                    if ($ocupados >= $turno_horario->cantidad_turnos) {
                        throw new \RuntimeException('El horario seleccionado ya tiene su cupo de delegaciones completo.');
                    }
                } else {
                    $ocupados = (int) Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario->id)
                        ->whereDate('fecha', $fecha)
                        ->where('hora', $horaFinal)
                        ->where('estado_id', '!=', 4)
                        ->sum('cantidad_personas');

                    if (($ocupados + $solicitadas) > $turno_horario->cantidad_turnos) {
                        $disponibles = max(0, $turno_horario->cantidad_turnos - $ocupados);
                        throw new \RuntimeException("El horario seleccionado no cuenta con cupo suficiente (disponibles: {$disponibles}, solicitados: {$solicitadas}).");
                    }
                }

                $fecha_hora = Carbon::createFromFormat('Y-m-d H:i:s', $fecha . ' ' . $horaFinal);
                $dependencia_codigo = optional(optional(optional($turno_horario->turno_tramite)->tramite)->dependencia)->codigo ?? 'OBS';

                return Turnos_Dependencias_Reservas::create([
                    'codigo' => Turnos_Dependencias_Reservas::generarCodigoUnico($dependencia_codigo),
                    'fecha_hora' => $fecha_hora,
                    'fecha' => $fecha,
                    'hora' => $horaFinal,
                    'nombre_apellido' => trim($request->input('nombre_apellido')),
                    'dni' => Turnos_Dependencias_Reservas::normalizarDni($request->input('dni')),
                    'celular' => $request->input('celular'),
                    'email' => $request->input('email'),
                    'es_grupal' => $solicitadas > 1 || $request->filled('nombre_institucion') || $usaCupoPorDelegacion,
                    'cantidad_personas' => $solicitadas,
                    'nombre_institucion' => $request->input('nombre_institucion'),
                    'cargo_responsable' => $request->input('cargo_responsable'),
                    'nivel_institucion' => $request->input('nivel_institucion'),
                    'turno_horario_id' => $turno_horario->id,
                    'dependencia_tramite_id' => $request->input('dependencia_tramite_id'),
                    'estado_id' => 3, // Confirmado por defecto en asignación de admin
                    'activo' => 1
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        if ($request->has('notificar_email') && !empty($reserva->email)) {
            try {
                Mail::to($reserva->email)->send(new \App\Mail\TurnoConfirmado($reserva));
            } catch (\Exception $e) {
                Log::error("Error enviando email de confirmación manual para reserva {$reserva->codigo}: " . $e->getMessage());
            }
        }

        return back()->with('success', "Reserva manual {$reserva->codigo} creada y confirmada con éxito para {$reserva->nombre_apellido}.");
    }

    public function edit($id)
    {
        $reserva = Turnos_Dependencias_Reservas::with('turno_tramite.tramite.dependencia')->find($id);

        if (!empty($reserva)) {
            return view('turnosdependenciasreservas.edit')->with(compact('reserva'));
        } else {
            return redirect(route('turnosdependenciasreservas.index'))->with('error', 'Error al editar reserva');
        }
    }

    public function index(BusquedaTurno $request)
    {
        $input = $request->all();

        $codigo_turno = $input['codigo_turno'] ?? null;
        $fecha_turno = $input['fecha_turno'] ?? null;
        $dependencia_id = $input['dependencia_id'] ?? null;
        $tramite_id = $input['tramite_id'] ?? null;
         
        $query = Turnos_Dependencias_Reservas::with('turno_horario.turno_tramite.tramite.dependencia');
        
        if ($codigo_turno) {
            $query->where('codigo', 'like', '%'.$codigo_turno.'%');
        }

        if ($fecha_turno) {
            $carbon_fecha = Carbon::createFromFormat('d/m/Y', $fecha_turno);
            $query->whereDate('fecha', $carbon_fecha);
        }

        if ($tramite_id) {
            $query->where('dependencia_turnos_reservas.dependencia_tramite_id', $tramite_id);
        }

        $query->join('turnos_horarios', 'dependencia_turnos_reservas.turno_horario_id', '=', 'turnos_horarios.id')
              ->join('turnos_tramites', 'turnos_horarios.turno_tramite_id', '=', 'turnos_tramites.id')
              ->join('dependencia_tramites', 'turnos_tramites.dependencia_tramite_id', '=', 'dependencia_tramites.id');

        if ($dependencia_id) {
            $query->where('dependencia_tramites.dependencia_id', $dependencia_id);
        }

        $usuario_id = Auth::id();
        $user_dependencias = DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id');
        $query->whereIn('dependencia_tramites.dependencia_id', $user_dependencias);

        $reservas = $query->select('dependencia_turnos_reservas.*')
                          ->orderBy('dependencia_turnos_reservas.fecha_hora', 'asc')
                          ->paginate(10);
        
        $dependencias = Dependencia::all()->pluck('nombre', 'id');
        $tramites = \App\Models\Dependencia_Tramite::all()->pluck('nombre', 'id');

        return view('turnosdependenciasreservas.index')
            ->with('reservas', $reservas)
            ->with('codigo_turno', $codigo_turno)
            ->with('fecha_turno', $fecha_turno)
            ->with('dependencias', $dependencias)
            ->with('tramites', $tramites)
            ->with('dependencia_id', $dependencia_id)
            ->with('tramite_id', $tramite_id);
    }

    public function print(BusquedaTurno $request)
    {
        $input = $request->all();
        
        $codigo_turno = $input['codigo_turno'] ?? null;
        $fecha_turno = $input['fecha_turno'] ?? null;
        $dependencia_id = $input['dependencia_id'] ?? null;
        $tramite_id = $input['tramite_id'] ?? null;
         
        $query = Turnos_Dependencias_Reservas::with('turno_horario.turno_tramite.tramite.dependencia');
        
        if ($codigo_turno) {
            $query->where('codigo', 'like', '%'.$codigo_turno.'%');
        }

        if ($fecha_turno) {
            $carbon_fecha = Carbon::createFromFormat('d/m/Y', $fecha_turno);
            $query->whereDate('fecha', $carbon_fecha);
        }

        if ($tramite_id) {
            $query->where('dependencia_turnos_reservas.dependencia_tramite_id', $tramite_id);
        }

        $query->join('turnos_horarios', 'dependencia_turnos_reservas.turno_horario_id', '=', 'turnos_horarios.id')
              ->join('turnos_tramites', 'turnos_horarios.turno_tramite_id', '=', 'turnos_tramites.id')
              ->join('dependencia_tramites', 'turnos_tramites.dependencia_tramite_id', '=', 'dependencia_tramites.id');

        if ($dependencia_id) {
            $query->where('dependencia_tramites.dependencia_id', $dependencia_id);
        }

        $usuario_id = Auth::id();
        $user_dependencias = DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id');
        $query->whereIn('dependencia_tramites.dependencia_id', $user_dependencias);

        $reservas = $query->with('integrantes')->select('dependencia_turnos_reservas.*')
                          ->orderBy('dependencia_turnos_reservas.fecha_hora', 'asc')
                          ->get();

        $html = view('htmltopdf.listado_reservas_turnos')
            ->with('reservas', $reservas)
            ->with('codigo_turno', $codigo_turno)
            ->with('fecha_turno', $fecha_turno)
            ->render();

        $pdf = \PDF::loadHTML($html);

        $html_header = view('htmltopdf.header_informe')
            ->render();

        return $pdf->download('reservas_turnos.pdf');
    }
}
