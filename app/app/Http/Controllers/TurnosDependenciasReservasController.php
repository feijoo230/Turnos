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
        $reserva = Turnos_Dependencias_Reservas::find($id);

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
        $reserva = Turnos_Dependencias_Reservas::find($id);

        if (!empty($reserva)) {
            return view('turnosdependenciasreservas.edit')->with(compact('reserva'));
        } else {
            return redirect(route('turnosdependenciasreservas.index'))->with('error', 'Error al editar reserva');
        }
    }

    public function index(BusquedaTurno $request)
    {

        $input = $request->all();

        if(!isset($input['codigo_turno'])) {
            $input['codigo_turno'] = null;
        }

        if(!isset($input['fecha_turno'])) {
            $input['fecha_turno'] = null;
        }

        if(!isset($input['dependencia_id'])) {
            $input['dependencia_id'] = null;
        }

        if(!isset($input['tramite_id'])) {
            $input['tramite_id'] = null;
        }
         
        $aWhere = array();
        $aWhereDate = array();
        
        if (!is_null($input['codigo_turno'])) {
            $aWhere[] = ['codigo', 'like', '%'.$input['codigo_turno'].'%'];
        }

        if (!is_null($input['fecha_turno'])) {
            $fecha_turno = Carbon::createFromFormat('d/m/Y', $input['fecha_turno']);
            $fecha_turno = $fecha_turno->startOfDay();
            $aWhereDate[] = ['fecha', '=', $fecha_turno];
        }

        if (!is_null($input['dependencia_id'])) {
            $aWhere[] = ['dependencia_turnos.dependencia_id', '=', $input['dependencia_id']];
        }

        if (!is_null($input['tramite_id'])) {
            $aWhere[] = ['dependencia_turnos_reservas.tramite_id', '=', $input['tramite_id']];
        }

        $usuario_id = Auth::id();

        $reservas = Turnos_Dependencias_Reservas::where($aWhere)
            ->where($aWhereDate)
            ->join('dependencia_turnos', 'dependencia_turnos_reservas.dependencia_turno_id', '=', 'dependencia_turnos.id')
            ->whereIn('dependencia_turnos.dependencia_id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())
            ->orderBy('dependencia_turnos_reservas.fecha_hora', 'asc')
            ->paginate(10);
        
        $dependencias = Dependencia::all()->pluck('nombre', 'id');
        $tramites = Tramite::all()->pluck('nombre', 'id');

        return view('turnosdependenciasreservas.index')
            ->with('reservas', $reservas)
            ->with('codigo_turno', $input['codigo_turno'])
            ->with('fecha_turno', $input['fecha_turno'])
            ->with('dependencias', $dependencias)
            ->with('tramites', $tramites)
            ->with('dependencia_id', $input['dependencia_id'])
            ->with('tramite_id', $input['tramite_id']);
    }

    public function print(BusquedaTurno $request)
    {
        $input = $request->all();
        
        $aWhere = array();
        $aWhereDate = array();
        
        if (!is_null($input['codigo_turno'])) {
            $aWhere[] = ['codigo', 'like', '%'.$input['codigo_turno'].'%'];
        }

        if (!is_null($input['fecha_turno'])) {
            $fecha_turno = Carbon::createFromFormat('d/m/Y', $input['fecha_turno']);
            $fecha_turno = $fecha_turno->startOfDay();
            $aWhereDate[] = ['fecha', '=', $fecha_turno];
        }

        $usuario_id = Auth::id();

        $reservas = Turnos_Dependencias_Reservas::where($aWhere)
            ->where($aWhereDate)
            ->join('dependencia_turnos', 'dependencia_turnos_reservas.dependencia_turno_id', '=', 'dependencia_turnos.id')
            ->whereIn('dependencia_turnos.dependencia_id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())
            ->orderBy('dependencia_turnos_reservas.fecha_hora', 'asc')
            ->get();

        $html = view('htmltopdf.listado_reservas_turnos')
            ->with('reservas', $reservas)
            ->with('codigo_turno', $input['codigo_turno'])
            ->with('fecha_turno', $input['fecha_turno'])
            ->render();

        $pdf = \PDF::loadHTML($html);

        $html_header = view('htmltopdf.header_informe')
            ->render();

        /*$options = array(
            'header-html' =>  '<!DOCTYPE html><div style="margin: 80px 10 10 80;">UNIVERSIDAD NACIONAL DE SALTA</div></html>'
        );
        $pdf->setOptions($options);*/

        return $pdf->download('reservas_turnos.pdf');
        //return $pdf->inline();
    }
}
