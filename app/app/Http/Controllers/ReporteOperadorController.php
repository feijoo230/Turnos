<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Turno;
use App\Http\Requests\SearchReporteOperadores;
use Illuminate\Support\Facades\DB;

class ReporteOperadorController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $operador_id = 0;
        $fecha_desde = 0;
        $fecha_hasta = 0;

        $whereData = array();

        $operadores_filtro = User::whereHas("roles", function($q){ $q->where("name", "OPERADOR"); })->pluck('name', 'id')->toArray();

        $turnos = Turno::where($whereData)->orderBy('id')->get();

        return view('reporteoperador.index')
            ->with('turnos', $turnos)
            ->with('operador_id', $operador_id)
            ->with('operadores_filtro', $operadores_filtro);
       
    }

    public function listado_operadores(SearchReporteOperadores $request)
    {
        $input = $request->all();

        $operador_id = 0;
        $fecha_desde = 0;
        $fecha_hasta = 0;
        $es_afiliado = FALSE;

        $whereData = array();

        $operadores_filtro = User::whereHas("roles", function($q){ $q->where("name", "OPERADOR"); })->pluck('name', 'id')->toArray();
    
        if (isset($input['operador_id']) && !is_null($input['operador_id'])) {            
            $operador_id = $input['operador_id'];
            array_push($whereData, ['operador_id', '=', $operador_id]);
        }

        if (isset($input['fecha_desde']) && !is_null($input['fecha_desde'])) {            
            $fecha_desde = $input['fecha_desde'];
            array_push($whereData, ['fecha_hora_ingreso', '>=',$fecha_desde]);
        }

        if (isset($input['fecha_hasta']) && !is_null($input['fecha_hasta'])) {            
            $fecha_hasta = $input['fecha_hasta'];
            array_push($whereData, [DB::raw('CAST(fecha_hora_egreso as date)'), '<=', $fecha_hasta]);
        }

        if (isset($input['es_afiliado'])) {            
            $es_afiliado = $input['es_afiliado'];
            array_push($whereData, ['clientes.es_afiliado', '=', $es_afiliado]);
        }

        $turnos = Turno::join('clientes', 'turnos.cliente_id', '=', 'clientes.id')->where($whereData)->orderBy('turnos.id')->get();

        //Cantidad Atenciones
        $cantidad_atenciones = Turno::leftJoin('clientes', 'turnos.cliente_id', '=', 'clientes.id')->where($whereData)->count();
        //Promedio
        $promedio = Turno::join('clientes', 'turnos.cliente_id', '=', 'clientes.id')->where($whereData)->sum('tiempo_atencion');
        if ($cantidad_atenciones > 0) {
            $promedio = $promedio / $cantidad_atenciones;
        } else {
            $promedio = 0;
        }
        

        return view('reporteoperador.index')
            ->with('turnos', $turnos)
            ->with('operador_id', $operador_id)
            ->with('promedio', $promedio)
            ->with('cantidad_atenciones', $cantidad_atenciones)
            ->with('operadores_filtro', $operadores_filtro)
            ->with('es_afiliado', $es_afiliado);
    }

    public function imprimir_listado(Request $request)
    {
        $input = $request->all();

        $operador_id = 0;
        $fecha_desde = 0;
        $fecha_hasta = 0;

        $whereData = array();

        if (isset($input['operador_id']) && !is_null($input['operador_id'])) {            
            $operador_id = $input['operador_id'];
            array_push($whereData, ['operador_id', '=', $operador_id]);
        }

        if (isset($input['fecha_desde']) && !is_null($input['fecha_desde'])) {            
            $fecha_desde = $input['fecha_desde'];
            array_push($whereData, ['fecha_hora_ingreso', '>=',$fecha_desde]);
        }

        if (isset($input['fecha_hasta']) && !is_null($input['fecha_hasta'])) {            
            $fecha_hasta = $input['fecha_hasta'];
            array_push($whereData, ['fecha_hora_egreso', '<=', $fecha_hasta]);
        }

        if (isset($input['es_afiliado'])) {            
            $es_afiliado = $input['es_afiliado'];
            array_push($whereData, ['clientes.es_afiliado', '=', $es_afiliado]);
        }

        $turnos = Turno::join('clientes', 'turnos.cliente_id', '=', 'clientes.id')->where($whereData)->orderBy('turnos.id')->get();

        //Cantidad Atenciones
        $cantidad_atenciones = Turno::join('clientes', 'turnos.cliente_id', '=', 'clientes.id')->where($whereData)->count();
        //Promedio
        $promedio = Turno::join('clientes', 'turnos.cliente_id', '=', 'clientes.id')->where($whereData)->sum('tiempo_atencion');
        if ($cantidad_atenciones > 0) {
            $promedio = $promedio / $cantidad_atenciones;
        } else {
            $promedio = 0;
        }

        //IMPRIMIR REPORTE
        $now = new \DateTime();

        $html = view('reporteoperador.informe-listado')
            ->with('turnos', $turnos)
            ->with('operador_id', $operador_id)
            ->with('promedio', $promedio)
            ->with('cantidad_atenciones', $cantidad_atenciones);

        $pdf = \PDF::loadHTML($html)
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-top', 38)
            ->setOption('margin-left', 20)
            ->setOption('margin-right', 10)
            ->setOption(
                'header-html', 
                '<!DOCTYPE html>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img style="padding: 0px; margin: 0px;" src="'.asset('img/logo.png').'" height="90"> 
                    <br>
                    <span>
                        GESTION DE TURNOS DE UNO SALUD           
                    </span>
                    <hr>
                    <br style="padding-bottom: 20px;">
            ')
            ->setOption(
                'footer-html', 
                '<!DOCTYPE html>
                <br style="padding-top: 0px; margin: 0px;">
                <hr>
                <span style="text-align: right;">UNO SALUD, '.$now->format('d/m/Y').'</span>');
        return $pdf->download('informe-gestor-turnos.pdf');
    }
}