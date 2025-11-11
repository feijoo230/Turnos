<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Models\Resolucion;
use App\Models\Resolucion_Estado;
use App\Http\Requests\StoreCargo;
use App\Models\Caracter;
use App\Models\Categorias;
use App\Models\Dependencia;
use App\Models\Estamento;
use App\Models\Situacion;
use App\Models\Cargo_Accion;
use App\Models\Tipo_Cargo;
use Illuminate\Pagination\Paginator;

class CargosController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $resoluciones = Resolucion::orderBy('numero', 'desc')
               ->orderBy('anio', 'desc')
               ->get();

        return view('cargos.index')
            ->with('resoluciones', $resoluciones);
    }

    public function listado($resolucion_id = null)
    {
        $resolucion = Resolucion::find($resolucion_id); 
        
        
        $cargos = Cargo::where('resolucion_id', $resolucion_id)
               ->orderBy('id', 'desc')
               ->paginate(5);

        return view('cargos.index')
            ->with('cargos', $cargos)
            ->with('resolucion', $resolucion);
    }

    public function show($id)
    {
        $cargo = Cargo::find($id);
        $resolucion = Resolucion::find($cargo->resolucion_id);

        if (empty($cargo)) {
            return 'ERROR!';
        }

        return view('cargos.show')
            ->with('cargo', $cargo)
            ->with('resolucion', $resolucion);
    }

    public function crear($resolucion_id = null)
    {
        $resolucion = Resolucion::find($resolucion_id);
        $cargo_accion = Cargo_Accion::all()->pluck('name', 'id')->toArray();
        $caracteres = Caracter::all()->pluck('name', 'id')->toArray();
        $categorias = Categorias::all()->pluck('name', 'id')->toArray();
        $dependencias = Dependencia::all()->pluck('name', 'id')->toArray();
        $estamentos = Estamento::all()->pluck('name', 'id')->toArray();
        $situaciones = Situacion::all()->pluck('name', 'id')->toArray();
        $tipos_cargos = Tipo_Cargo::all()->where('estamento_id', Estamento::NO_DOC)->pluck('name', 'id')->toArray();

        $dependencias = Dependencia::descendantsAndSelf(Dependencia::ROOT_ID)->toTree();
        $result = array();
        Dependencia::selectTree($dependencias, '&#8212;>', $result);
        $dependencias = $result;
        return view('cargos.create')
            ->with(compact('resolucion', 'caracteres','categorias', 'dependencias','estamentos', 'situaciones', 'cargo_accion', 'tipos_cargos'));
    }

    public function create()
    {
    }

    public function store(StoreCargo $request)
    {
        $input = $request->all();

        try{
            $cargo = Cargo::create($input);

            return redirect(url('cargos.listado', [$input['resolucion_id']] ))->with('success','La resolución se registro correctamente.');
        } catch (\Exception $e) {
            return redirect(url('cargos.listado', [$input['resolucion_id']] ))->with('error','Hubo un error en el registro de la resolución. Por favor intente nuevamente.');
        }
    }

    public function edit($id)
    {
        $cargo = Cargo::find($id);
        $resolucion = Resolucion::find($cargo->resoluciones->id);
        
        $cargo_accion = Cargo_Accion::all()->pluck('name', 'id')->toArray();
        $caracteres = Caracter::all()->pluck('name', 'id')->toArray();
        $categorias = Categorias::all()->pluck('name', 'id')->toArray();
        $dependencias = Dependencia::all()->pluck('name', 'id')->toArray();
        $estamentos = Estamento::all()->pluck('name', 'id')->toArray();
        $situaciones = Situacion::all()->pluck('name', 'id')->toArray();
         $tipos_cargos = Tipo_Cargo::all()->where('estamento_id', Estamento::NO_DOC)->pluck('name', 'id')->toArray();

        $dependencias = Dependencia::descendantsAndSelf(Dependencia::ROOT_ID)->toTree();
        $result = array();
        Dependencia::selectTree($dependencias, '&#8212;>', $result);
        $dependencias = $result;
        
        return view('cargos.edit')
            ->with(compact('cargo','resolucion', 'caracteres','categorias', 'dependencias','estamentos', 'situaciones', 'cargo_accion', 'tipos_cargos'));
    }
    
    public function update($id, StoreCargo $request)
    {
        $cargo = cargo::find($id);
        $cargo->name = $request['name'];
        $cargo->misiones_funciones = $request['misiones_funciones'];
        $cargo->tipo_cargo_id = $request['tipo_cargo_id'];
        $cargo->cargo_accion_id = $request['cargo_accion_id'];
        $cargo->caracter_id = $request['caracter_id'];
        $cargo->categoria_id = $request['categoria_id'];
        $cargo->dependencia_id = $request['dependencia_id'];
        $cargo->estamento_id = $request['estamento_id'];
        $cargo->resolucion_id = $request['resolucion_id'];

        try{
            $cargo->save();

            return redirect(url('cargos.listado', [$cargo->resolucion_id]))->with('success','La resolución se actualizo correctamente.');
        } catch (\Exception $e) {
            return redirect(url('cargos.listado', [$cargo->resolucion_id]))->with('error','Hubo un error en la actualizació
                n de la resolución. Por favor intente nuevamente.');
        }
    }

    public function destroy($id)
    {
        $cargo = Cargo::find($id);

        if (empty($cargo)) {
            Flash::error('Permiso not found');

            return redirect(route('cargos.index'));
        }

        try{
            $cargo->delete();
            return redirect(url('cargos.listado', [$cargo->resolucion_id]))->with('success','La resolución se elimino correctamente.');
        } catch (\Exception $e) {
            return redirect(url('cargos.listado', [$cargo->resolucion_id]))->with('error','Hubo un error en la eliminación de la resolución. Por favor intente nuevamente.');
        }
    }

    public function favorito($id)
    {
        
    }
}
