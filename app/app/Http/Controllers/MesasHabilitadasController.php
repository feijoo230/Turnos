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
        $mesashabilitadas = Mesas_Habilitadas::with(['dependencia.parent', 'dependencia.tipoDependencia'])->get();

        $stats = [
            'total' => $mesashabilitadas->count(),
            'activas' => $mesashabilitadas->where('activo', 1)->count(),
            'inactivas' => $mesashabilitadas->where('activo', 0)->count(),
            'total_dependencias' => Dependencia::count()
        ];

        return view('mesashabilitadas.index')
            ->with('mesashabilitadas', $mesashabilitadas)
            ->with('stats', $stats);
    }

    public function toggle($id)
    {
        $mesahabilitada = Mesas_Habilitadas::find($id);

        if ($mesahabilitada) {
            $mesahabilitada->activo = $mesahabilitada->activo ? 0 : 1;
            $mesahabilitada->save();
        }

        return redirect(route('mesashabilitadas.index'));
    }

    public function show($id)
    {
        $mesahabilitada = Mesas_Habilitadas::with(['dependencia.parent'])->find($id);

        if (empty($mesahabilitada)) {
            return redirect(route('mesashabilitadas.index'));
        }

        return view('mesashabilitadas.show')->with('mesahabilitada', $mesahabilitada);
    }

    public function create()
    {
        $dependencias = Dependencia::whereNotIn('id', DB::table('mesas_habilitadas')->where('activo', 1)->pluck('dependencia_id')->toArray())
            ->orderBy('nombre')
            ->get()
            ->mapWithKeys(function ($dep) {
                return [$dep->id => $dep->string_path];
            })
            ->toArray();

        if (empty($dependencias)) {
            $dependencias = Dependencia::orderBy('nombre')->get()->mapWithKeys(function ($dep) {
                return [$dep->id => $dep->string_path];
            })->toArray();
        }

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

        $dependencias = Dependencia::orderBy('nombre')->get()->mapWithKeys(function ($dep) {
            return [$dep->id => $dep->string_path];
        })->toArray();

        return view('mesashabilitadas.edit')->with('mesahabilitada', $mesahabilitada)->with(compact('dependencias'));
    }

    public function update($id, Request $request)
    {
        $mesahabilitada = Mesas_Habilitadas::find($id);
        $mesahabilitada->activo = $request->has('activo') ? 1 : 0;

        $mesahabilitada->save();

        return redirect(route('mesashabilitadas.index'));
    }

    public function destroy($id)
    {
        $mesahabilitada = Mesas_Habilitadas::find($id);
        if ($mesahabilitada) {
            $mesahabilitada->delete();
        }

        return redirect(route('mesashabilitadas.index'));
    }
}
