<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turnos_Dependencias;
use App\Models\Dependencia;
use App\Models\Usuariodependencia;
use App\Http\Requests\StoreTurnoDependencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TurnosDependenciasController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $usuario_id = Auth::id();
        
        $turnosdependencias = Turnos_Dependencias::whereIn('dependencia_id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('turnosdependencias.index')
            ->with('turnosdependencias', $turnosdependencias);
    }

    public function show($id)
    {
        $turnodependencia = Turnos_Dependencias::find($id);

        if (empty($turnodependencia)) {
            return 'ERROR!';
        }

        return view('turnosdependencias.show')->with('turnodependencia', $turnodependencia);
    }

    public function create()
    {
        $usuario_id = Auth::id();

        $dependencias = Dependencia::whereIn('id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())->orderBy('nombre')->pluck('nombre', 'id')->toArray();

        return view('turnosdependencias.create')->with(compact('dependencias'));
    }

    public function store(StoreTurnoDependencia $request)
    {
        $input = $request->all();
       
        $input['fecha_desde'] = Carbon::createFromFormat('d/m/Y', $input['fecha_desde']);
        $input['fecha_hasta'] = Carbon::createFromFormat('d/m/Y', $input['fecha_hasta']);

        $turnodependencia = Turnos_Dependencias::create($input);

        return redirect(route('turnosdependencias.index'));
    }

    public function edit($id)
    {
        $turnodependencia = Turnos_Dependencias::find($id);
        $usuario_id = Auth::id();

        $dependencias = Dependencia::whereIn('id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())->orderBy('nombre')->pluck('nombre', 'id')->toArray();

        return view('turnosdependencias.edit')->with(compact('turnodependencia','dependencias'));
    }

    public function update($id, StoreTurnoDependencia $request)
    {
        $turnodependencia = Turnos_Dependencias::find($id);
        $turnodependencia->intervalo = $request['intervalo'];
        $turnodependencia->hora_desde = $request['hora_desde'];
        $turnodependencia->hora_hasta = $request['hora_hasta'];
        $turnodependencia->fecha_desde = Carbon::createFromFormat('d/m/Y', $request['fecha_desde']);
        $turnodependencia->fecha_hasta = Carbon::createFromFormat('d/m/Y', $request['fecha_hasta']);
        $turnodependencia->dependencia_id = $request['dependencia_id'];
        $turnodependencia->lunes = $request['lunes'];
        $turnodependencia->martes = $request['martes'];
        $turnodependencia->miercoles = $request['miercoles'];
        $turnodependencia->jueves = $request['jueves'];
        $turnodependencia->viernes = $request['viernes'];

        $turnodependencia->save();

        return redirect(route('turnosdependencias.index'));
    }

    public function destroy($id)
    {
        $turnodependencia = Turnos_Dependencias::find($id);

        if (empty($turnodependencia)) {
            Flash::error('Permiso not found');

            return redirect(route('turnosdependencias.index'));
        }

        $turnodependencia->delete();

        return redirect(route('turnosdependencias.index'));
    }
}
