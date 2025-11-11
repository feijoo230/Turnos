<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function index(BusquedaTurno $request)
    {

        $input = $request->all();

        if(!isset($input['codigo_turno'])) {
            $input['codigo_turno'] = null;
        }

        if(!isset($input['fecha_turno'])) {
            $input['fecha_turno'] = null;
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

        $usuario_id = Auth::id();

        $reservas = Turnos_Dependencias_Reservas::where($aWhere)
            ->where($aWhereDate)
            ->join('dependencia_turnos', 'dependencia_turnos_reservas.dependencia_turno_id', '=', 'dependencia_turnos.id')
            ->whereIn('dependencia_turnos.dependencia_id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())
            ->orderBy('dependencia_turnos_reservas.fecha_hora', 'asc')
            ->paginate(10);
        
        return view('turnosdependenciasreservas.index')
            ->with('reservas', $reservas)
            ->with('codigo_turno', $input['codigo_turno'])
            ->with('fecha_turno', $input['fecha_turno']);
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
