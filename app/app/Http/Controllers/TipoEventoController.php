<?php

namespace App\Http\Controllers;

use App\Models\TipoEvento;
use Illuminate\Http\Request;

class TipoEventoController extends Controller
{
    public function index()
    {
        $tipos = TipoEvento::orderBy('id', 'desc')->paginate(15);
        return view('tipos_evento.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_evento.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'activo' => 'required|boolean'
        ]);

        TipoEvento::create($request->all());
        return redirect(route('tipos-evento.index'))->with('success', 'Tipo de evento guardado correctamente.');
    }

    public function edit($id)
    {
        $tipo = TipoEvento::findOrFail($id);
        return view('tipos_evento.edit', compact('tipo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'activo' => 'required|boolean'
        ]);

        $tipo = TipoEvento::findOrFail($id);
        $tipo->update($request->all());
        return redirect(route('tipos-evento.index'))->with('success', 'Tipo de evento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $tipo = TipoEvento::findOrFail($id);
        if ($tipo->turnos_tramites()->count() > 0) {
            return redirect(route('tipos-evento.index'))->with('error', 'No se puede eliminar porque tiene turnos asociados.');
        }
        $tipo->delete();
        return redirect(route('tipos-evento.index'))->with('success', 'Tipo de evento eliminado correctamente.');
    }
}
