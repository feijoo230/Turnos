<?php

namespace App\Http\Controllers;

use App\Models\ProyectoExtension;
use Illuminate\Http\Request;

class ProyectoExtensionController extends Controller
{
    public function index()
    {
        $proyectos = ProyectoExtension::orderBy('id', 'desc')->paginate(15);
        return view('proyectos_extension.index', compact('proyectos'));
    }

    public function create()
    {
        return view('proyectos_extension.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean'
        ]);

        ProyectoExtension::create($request->all());
        return redirect(route('proyectos-extension.index'))->with('success', 'Proyecto guardado correctamente.');
    }

    public function edit($id)
    {
        $proyecto = ProyectoExtension::findOrFail($id);
        return view('proyectos_extension.edit', compact('proyecto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean'
        ]);

        $proyecto = ProyectoExtension::findOrFail($id);
        $proyecto->update($request->all());
        return redirect(route('proyectos-extension.index'))->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $proyecto = ProyectoExtension::findOrFail($id);
        if ($proyecto->turnos_tramites()->count() > 0) {
            return redirect(route('proyectos-extension.index'))->with('error', 'No se puede eliminar porque tiene turnos asociados.');
        }
        $proyecto->delete();
        return redirect(route('proyectos-extension.index'))->with('success', 'Proyecto eliminado correctamente.');
    }
}
