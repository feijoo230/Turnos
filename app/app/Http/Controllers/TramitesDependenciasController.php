<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dependencia_Tramite;
use App\Models\Dependencia;
use App\Http\Requests\StoreTramitesDependecia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TramitesDependenciasController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $usuario_id = Auth::id();
        
        $tramitesdependencia = Dependencia_Tramite::whereIn('dependencia_id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tramitesdependencias.index')
            ->with('tramitesdependencia', $tramitesdependencia);
    }

    public function show($id)
    {
        $tramitedependencia = Dependencia_Tramite::find($id);

        if (empty($tramitedependencia)) {
            return 'ERROR!';
        }

        return view('tramitesdependencias.show')->with('tramitedependencia', $tramitedependencia);
    }

    public function create()
    {
        $usuario_id = Auth::id();

        $dependencias = Dependencia::whereIn('id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())->orderBy('nombre')->pluck('nombre', 'id')->toArray();

        return view('tramitesdependencias.create')->with(compact('dependencias'));
    }

    public function store(StoreTramitesDependecia $request)
    {
        $input = $request->all();
        // Si no se ha configurado 'activo' entonces guardamos el tramite como no activo
        $input['activo'] = isset($input['activo']); 
        $tramitedependencia = Dependencia_Tramite::create($input);

        return redirect(route('tramitesdependencias.index'));
    }

    public function edit($id)
    {
        $tramitedependencia = Dependencia_Tramite::find($id);
        $usuario_id = Auth::id();

        $dependencias = Dependencia::whereIn('id', DB::table('usuarios_dependencias')->where('usuario_id', $usuario_id)->pluck('dependencia_id')->toArray())->orderBy('nombre')->pluck('nombre', 'id')->toArray();

        return view('tramitesdependencias.edit')->with(compact('tramitedependencia', 'dependencias'));
    }

    public function update($id, StoreTramitesDependecia $request)
    {
        $tramitedependencia = Dependencia_Tramite::find($id);
        $tramitedependencia->nombre = $request['nombre'];
        $tramitedependencia->dependencia_id = $request['dependencia_id'];
        $tramitedependencia->activo = isset($request['activo'])? $request['activo'] : 0;

        $tramitedependencia->save();

        return redirect(route('tramitesdependencias.index'));
    }

    public function destroy($id)
    {
        $tramitedependencia = Dependencia_Tramite::find($id);

        if (empty($tramitedependencia)) {
            Flash::error('Permiso not found');

            return redirect(route('tramitesdependencias.index'));
        }

        $tramitedependencia->delete();

        return redirect(route('tramitesdependencias.index'));
    }
}
