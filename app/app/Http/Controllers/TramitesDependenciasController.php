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
        $input['activo'] = $request->has('activo'); 
        $modalidad = $request->input('tipo_modalidad', 'individual');
        $input['tipo_modalidad'] = $modalidad;
        $input['permite_grupal'] = ($modalidad !== 'individual');

        if ($modalidad === 'individual') {
            $input['max_personas_reserva'] = 1;
            $input['min_personas_reserva'] = 1;
            $input['requiere_institucion'] = false;
            $input['requiere_nomina'] = false;
        } else {
            $input['requiere_institucion'] = $request->has('requiere_institucion');
            $input['requiere_nomina'] = $request->has('requiere_nomina');
            $input['max_personas_reserva'] = $request->input('max_personas_reserva', 10);
            $input['min_personas_reserva'] = $request->input('min_personas_reserva', 1);
        }

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
        $tramitedependencia = Dependencia_Tramite::findOrFail($id);
        $input = $request->all();
        $input['activo'] = $request->has('activo'); 
        $modalidad = $request->input('tipo_modalidad', 'individual');
        $input['tipo_modalidad'] = $modalidad;
        $input['permite_grupal'] = ($modalidad !== 'individual');

        if ($modalidad === 'individual') {
            $input['max_personas_reserva'] = 1;
            $input['min_personas_reserva'] = 1;
            $input['requiere_institucion'] = false;
            $input['requiere_nomina'] = false;
        } else {
            $input['requiere_institucion'] = $request->has('requiere_institucion');
            $input['requiere_nomina'] = $request->has('requiere_nomina');
            $input['max_personas_reserva'] = $request->input('max_personas_reserva', 10);
            $input['min_personas_reserva'] = $request->input('min_personas_reserva', 1);
        }

        $tramitedependencia->update($input);

        return redirect(route('tramitesdependencias.index'));
    }

    public function destroy($id)
    {
        try {
            $tramitedependencia = Dependencia_Tramite::find($id);

            if (empty($tramitedependencia)) {
                return redirect(route('tramitesdependencias.index'))->with('error', 'Trámite no encontrado');
            }

            $tramitedependencia->delete();

            return redirect(route('tramitesdependencias.index'))->with('success', 'Trámite eliminado con éxito.');
        } catch (\Exception $e) {
            return redirect(route('tramitesdependencias.index'))->with('error', 'No se puede eliminar el trámite porque posee datos o reservas asociadas.');
        }
    }
}
