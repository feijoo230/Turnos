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
        $reserva = Turnos_Dependencias_Reservas::find($id);

        if (empty($reserva)) {
            return back()->with('error', 'Reserva no encontrada');
        }

        $nuevoEstado = $request->input('estado_id', 1);
        $reserva->estado_id = $nuevoEstado;
        $reserva->save();

        if ($nuevoEstado == 3 && !empty($reserva->email)) {
            try {
                Mail::to($reserva->email)->send(new \App\Mail\TurnoConfirmado($reserva));
            } catch (\Exception $e) {
                Log::error("Error al enviar mail de confirmación para turno {$reserva->codigo}: " . $e->getMessage());
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
                $fecha = Carbon::createFromFormat('d/m/Y', $fecha_raw)->format('Y-m-d');
                $diaSemana = Carbon::createFromFormat('d/m/Y', $fecha_raw)->dayOfWeekIso;
            } else {
                $fecha = Carbon::parse($fecha_raw)->format('Y-m-d');
                $diaSemana = Carbon::parse($fecha_raw)->dayOfWeekIso;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Formato de fecha inválido'], 400);
        }

        $turno_tramite = \App\Models\Turnos_Tramites::where('dependencia_tramite_id', $dependencia_tramite_id)->first();
        if (!$turno_tramite) {
            return response()->json([], 200);
        }

        $horarios = \App\Models\Turnos_Horarios::where('turno_tramite_id', $turno_tramite->id)
            ->where('dia_id', $diaSemana)
            ->where('activo', 1)
            ->get();

        $reservas = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $dependencia_tramite_id)
            ->whereDate('fecha', $fecha)
            ->where('estado_id', '!=', 4)
            ->select('turno_horario_id', DB::raw('sum(cantidad_personas) as total_personas'))
            ->groupBy('turno_horario_id')
            ->pluck('total_personas', 'turno_horario_id')
            ->toArray();

        $resultado = [];
        foreach ($horarios as $h) {
            $ocupados = $reservas[$h->id] ?? 0;
            $disponibles = max(0, $h->cantidad_turnos - $ocupados);
            $resultado[] = [
                'id' => $h->id,
                'hora' => $h->hora,
                'cantidad_turnos' => $h->cantidad_turnos,
                'ocupados' => $ocupados,
                'disponibles' => $disponibles,
                'label' => "{$h->hora} hs (Disponibles: {$disponibles} / Capacidad: {$h->cantidad_turnos})"
            ];
        }

        return response()->json($resultado);
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'dependencia_tramite_id' => 'required|integer',
            'fecha_reserva' => 'required|string',
            'turno_horario_id' => 'required|integer',
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
                $fecha = Carbon::createFromFormat('d/m/Y', $request->input('fecha_reserva'))->format('Y-m-d');
            } else {
                $fecha = Carbon::parse($request->input('fecha_reserva'))->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Formato de fecha inválido.');
        }

        $turno_horario = \App\Models\Turnos_Horarios::find($request->input('turno_horario_id'));
        if (!$turno_horario) {
            return back()->withInput()->with('error', 'El horario seleccionado no es válido.');
        }

        $solicitadas = (int) $request->input('cantidad_personas', 1);
        if ($request->boolean('bloquear_cupo_completo')) {
            $solicitadas = max($solicitadas, $turno_horario->cantidad_turnos);
        }

        $hora = $turno_horario->hora;
        $fecha_hora_str = Carbon::parse($fecha)->format('d/m/Y') . ' ' . $hora;
        $fecha_hora = Carbon::createFromFormat('d/m/Y H:i:s', strlen($hora) == 5 ? $fecha_hora_str . ':00' : $fecha_hora_str);

        $reservaData = [
            'fecha_hora' => $fecha_hora,
            'fecha' => $fecha,
            'hora' => $hora,
            'nombre_apellido' => $request->input('nombre_apellido'),
            'dni' => $request->input('dni'),
            'celular' => $request->input('celular'),
            'email' => $request->input('email'),
            'es_grupal' => $solicitadas > 1 || $request->filled('nombre_institucion'),
            'cantidad_personas' => $solicitadas,
            'nombre_institucion' => $request->input('nombre_institucion'),
            'cargo_responsable' => $request->input('cargo_responsable'),
            'nivel_institucion' => $request->input('nivel_institucion'),
            'turno_horario_id' => $turno_horario->id,
            'dependencia_tramite_id' => $request->input('dependencia_tramite_id'),
            'estado_id' => 3, // Confirmado por defecto en asignación de admin
            'activo' => 1
        ];

        $reserva = Turnos_Dependencias_Reservas::create($reservaData);

        $dependencia_codigo = $reserva->turno_horario->turno_tramite->tramite->dependencia->codigo ?? 'TUR';
        $reserva->codigo = $dependencia_codigo . str_pad($reserva->id, 6, "0", STR_PAD_LEFT);
        $reserva->save();

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
