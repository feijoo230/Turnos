<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mesas_Habilitadas;
use App\Models\Dependencia;


class MesasHabilitadasController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $mesashabilitadas = Mesas_Habilitadas::get();

        return view('mesashabilitadas.index')
            ->with('mesashabilitadas', $mesashabilitadas);
    }

    public function show($id)
    {
        $mesahabilitada =Mesas_Habilitadas::find($id);

        if (empty($rol)) {
            return 'ERROR!';
        }

        return view('mesashabilitadas.show')->with('rol', $rol);
    }

    public function create()
    {
        $dependencias = Dependencia::whereNotIn('id', DB::table('mesas_habilitadas')->where('activo', 1)->pluck('dependencia_id')->toArray())->orderBy('nombre')->pluck('nombre', 'id')->toArray();

        return view('mesashabilitadas.create')->with(compact('dependencias'));
    }

    public function store(Request $request)
    {
        $activo = $request->has('activo') ? 1 : 0;
        $mesahabilitada = new Mesas_Habilitadas(
            [
                'dependencia_id' => $request['dependencia_id'],
                'activo' => $activo,
            ]
        );
        
        $mesahabilitada->save();

        return redirect(route('mesashabilitadas.index'));
    }

    public function edit($id)
    {
        $mesahabilitada = Mesas_Habilitadas::find($id);

        $dependencias = Dependencia::whereNotIn('id', DB::table('mesas_habilitadas')->where('activo', 1)->pluck('dependencia_id')->toArray())->orderBy('nombre')->pluck('nombre', 'id')->toArray();

        return view('mesashabilitadas.edit')->with('mesahabilitada', $mesahabilitada)->with(compact('dependencias'));
    }

    public function update($id, Request $request)
    {
        $mesahabilitada = Mesas_Habilitadas::find($id);
        $mesahabilitada->activo = $request['activo'];

        $mesahabilitada->save();

        return redirect(route('mesashabilitadas.index'));
    }

    public function destroy($id)
    {
        $mesahabilitada =Mesas_Habilitadas::find($id);
        $mesahabilitada->activo = 0;

        $mesahabilitada->save();

        return redirect(route('mesashabilitadas.index'));
    }
}
