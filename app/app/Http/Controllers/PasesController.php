<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pases;
use App\Models\Tramite;
use App\Models\Dependencia;
use Illuminate\Support\Facades\Auth;

class PasesController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $pases = Pases::all();

        return view('pases.index')
            ->with('pases', $pases);
    }

    public function listar($tramite_id)
    {
        $pases = Pases::where('id', $tramite_id);
        $tramite = Tramite::find($tramite_id);

        return view('pases.index')
            ->with(compact('pases', 'tramite'));
    }

    public function show($id)
    {
        $rol = Pases::find($id);

        if (empty($rol)) {
            return 'ERROR!';
        }

        return view('pases.show')->with('rol', $rol);
    }

    public function create()
    {
        $usuario = Auth::user();
        
        $dependencias = Dependencia::defaultOrder()->get()
            ->toTree();

        $result = array();
        Dependencia::selectTree($dependencias, '', $result);
        $dependencias = $result;

        return view('pases.create')->with(compact('usuario', 'dependencias'));
    }

    public function store(StoreRol $request)
    {
        $rol = new Role(
            [
                'name' => $request['name']
            ]
        );
        
        $rol->save();

        return redirect(route('pases.index'));
    }

    public function edit($id)
    {
        $rol = Pases::find($id);

        return view('pases.edit')->with('rol', $rol);
    }

    public function update($id, StoreRol $request)
    {
        $rol = Pases::find($id);
        $rol->name = $request['name'];

        $rol->save();

        return redirect(route('pases.index'));
    }

    public function destroy($id)
    {
        $rol = Pases::find($id);

        if (empty($rol)) {
            Flash::error('Permiso not found');

            return redirect(route('pases.index'));
        }

        $rol->delete();

        return redirect(route('pases.index'));
    }
}
