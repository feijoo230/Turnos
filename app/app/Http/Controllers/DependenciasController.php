<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dependencia;
use App\Models\Tipo_Dependencia;
use App\Http\Requests\StoreDependencia;

class DependenciasController extends Controller
{

    var $result = array();

    public function __construct()
    {

    }

    public function index()
    {
        $dependencias = Dependencia::defaultOrder()->get()
            ->toTree();

        $result = array();
        Dependencia::tableTreeWithNivel($dependencias, '', $result);

        return view('dependencias.index')
            ->with('dependencias', $result);
    }

    public function create()
    {
        $dependencias = Dependencia::defaultOrder()->descendantsAndSelf(Dependencia::ROOT_ID)->toTree();
        $result = array();
        Dependencia::selectTreeWithNivel($dependencias, '', $result);
        $dependencias = $result;

        $tipos_dependencias = Tipo_Dependencia::all()->pluck('name', 'id')->toArray();        

        return view('dependencias.create')->with(compact('dependencias', 'tipos_dependencias'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $parent = Dependencia::find($request['parent_id']);
        $input['nivel'] = (!empty($input['nivel']))? $input['nivel'] : ($parent->nivel + 1);
        $dependencia = Dependencia::create($input);
        
        $dependencia->parent()->associate($parent)->save();

        return redirect(route('dependencias.index'));
    }

    public function edit($id)
    {
        $dependencias = Dependencia::descendantsAndSelf(Dependencia::ROOT_ID)->toTree();
        $result = array();
        Dependencia::selectTreeWithNivel($dependencias, '', $result);
    
        $dependencia = Dependencia::find($id);
        $tipos_dependencias = Tipo_Dependencia::all()->pluck('name', 'id')->toArray();

        return view('dependencias.edit')
            ->with('tipos_dependencias', $tipos_dependencias)
            ->with('dependencia', $dependencia)
            ->with('dependencias', $result);
    }

    public function update($id, StoreDependencia $request)
    {
        $dependencia = Dependencia::find($id);
        $dependencia->nombre = $request['nombre'];
        $dependencia->codigo = $request['codigo'];
        $dependencia->es_unidad_academica = $request['es_unidad_academica'];
        $dependencia->parent_id = $request['parent_id'];
        $dependencia->tipo_dependencia_id = $request['tipo_dependencia_id'];
        
        $parent = Dependencia::find($request['parent_id']);
        $dependencia->nivel = (!empty($request['nivel']))? $request['nivel'] : ($parent->nivel + 1);
        $dependencia->parent()->associate($parent)->save();

        $dependencia->save();

        return redirect(route('dependencias.index'));
    }

    public function destroy($id)
    {
        $dependencia = Dependencia::find($id);

        if (empty($dependencia)) {
            Flash::error('Permiso not found');

            return redirect(route('dependencias.index'));
        }

        $dependencia->delete();

        return redirect(route('dependencias.index'));
    }
}
