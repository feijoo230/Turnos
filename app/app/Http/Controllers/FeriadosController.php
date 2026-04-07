<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feriado;
use App\Http\Requests\StoreFeriado;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FeriadosImport;
use App\Exports\FeriadosExport;

class FeriadosController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $feriados = Feriado::all();

        return view('feriados.index')
            ->with('feriados', $feriados);
    }

    public function show($id)
    {
        $feriado = Feriado::find($id);

        if (empty($feriado)) {
            return 'ERROR!';
        }

        return view('feriados.show')->with('feriado', $feriado);
    }

    public function create()
    {
        return view('feriados.create');
    }

    public function store(StoreFeriado $request)
    {
        $feriado = new Feriado(
            [
                'fecha' => Carbon::createFromFormat('d/m/Y', $request['fecha']),
                'observacion' => $request['observacion'],
                'activo' => 1
            ]
        );
        
        $feriado->save();

        return redirect(route('feriados.index'));
    }

    public function edit($id)
    {
        $feriado = Feriado::find($id);

        return view('feriados.edit')->with('feriado', $feriado);
    }

    public function update($id, StoreFeriado $request)
    {
        $feriado = Feriado::find($id);
        $feriado->fecha = Carbon::createFromFormat('d/m/Y', $request['fecha']);
        $feriado->observacion = $request['observacion'];

        $feriado->save();

        return redirect(route('feriados.index'));
    }

    public function destroy($id)
    {
        $feriado = Feriado::find($id);

        if (empty($feriado)) {
            Flash::error('Permiso not found');

            return redirect(route('feriados.index'));
        }

        $feriado->delete();

        return redirect(route('feriados.index'));
    }

    public function import(Request $request) 
    {
        $request->validate([
            'excel' => 'required|mimes:xls,xlsx'
        ]);

        Excel::import(new FeriadosImport, $request->file('excel'));
        
        return redirect(route('feriados.index'))->with('success', 'Feriados importados correctamente.');
    }

    public function export() 
    {
        return Excel::download(new FeriadosExport, 'feriados.xlsx');
    }
}
