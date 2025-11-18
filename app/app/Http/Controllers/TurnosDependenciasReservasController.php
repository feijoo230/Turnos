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

class TurnosDependenciasReservasController extends Controller
{
    public function __construct()
    {

    }

    public function show($id)
    {
        $reserva = Turnos_Dependencias_Reservas::with('turno_tramite.tramite.dependencia')->find($id);

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
         
        $query = Turnos_Dependencias_Reservas::with('turno_horario.turnoTramite.tramite.dependencia');
        
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
         
        $query = Turnos_Dependencias_Reservas::with('turno_horario.turnoTramite.tramite.dependencia');
        
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
