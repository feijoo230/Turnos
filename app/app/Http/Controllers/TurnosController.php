<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\Turno;
use Carbon\Carbon;

class TurnosController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $turnos = Turno::where('fecha_hora_ingreso', NULL)->orderBy('id', 'ASC')->get();

        $turnos_atendidos = Turno::where('fecha_hora_egreso', '<>' , NULL)->orderBy('id', 'DESC')->get();

        $turno_en_curso = Turno::where('en_curso', TRUE)->get();

        //dd($turno_en_curso);

        return view('turnos.index')
            ->with('turnos', $turnos)->with('turnos_atendidos', $turnos_atendidos)->with('turno_en_curso', $turno_en_curso);
    }

    public function llamar_siguiente()
    {
        $turno_terminado = Turno::where('en_curso', TRUE)->orderBy('id', 'ASC')->first();

        if (isset($turno_terminado)) {
            $turno_terminado->fecha_hora_egreso = new \DateTime();
            $turno_terminado->en_curso = FALSE;
            
            $date_egreso = Carbon::parse($turno_terminado->fecha_hora_egreso);
            //var_dump($date_egreso);
            $date_ingreso = Carbon::parse($turno_terminado->fecha_hora_ingreso);
            //dd($date_ingreso);
            $turno_terminado->tiempo_atencion = $date_ingreso->diffInMinutes($date_egreso);
            
            $turno_terminado->save();
        }

        $turno_inicia = Turno::where('en_curso', NULL)->orderBy('id', 'ASC')->first();

        if (isset($turno_inicia)) {
            $turno_inicia->fecha_hora_ingreso = new \DateTime();
            $turno_inicia->en_curso = TRUE;
            $turno_inicia->operador_id = auth()->user()->id;
            $turno_inicia->save();
        }

        return redirect(url('turnos_admin'));
    }

    public function terminar_turno($id_turno)
    {
        $turno = Turno::find($id_turno);
        
        $turno->fecha_hora_egreso = new \DateTime();
        $turno->en_curso = FALSE;
        
        $date_egreso = Carbon::parse($turno->fecha_hora_egreso);
        //var_dump($date_egreso);
        $date_ingreso = Carbon::parse($turno->fecha_hora_ingreso);
        //dd($date_ingreso);
        $turno->tiempo_atencion = $date_ingreso->diffInMinutes($date_egreso);
        
        $turno->save();

        return redirect(url('turnos_admin'));
    }

    public function es_afiliado($id_turno)
    {
        $turno = Turno::find($id_turno);
        
        $turno->cliente->es_afiliado = !$turno->cliente->es_afiliado;
        
        $turno->cliente->save();

        return redirect(url('turnos_admin'));
    }
}
