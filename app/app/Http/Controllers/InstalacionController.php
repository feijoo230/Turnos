<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstalacionController extends Controller
{
    public function index()
    {
        $instalaciones = Instalacion::orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(15);

        return view('instalaciones.index', compact('instalaciones'));
    }

    public function create()
    {
        return view('instalaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'icono' => 'nullable|string|max:100',
            'descripcion' => 'required|string',
            'caracteristicas' => 'nullable|string',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'orden' => 'nullable|integer',
            'activo' => 'required|boolean'
        ]);

        $data = $request->except(['imagen_file']);
        $data['orden'] = $request->input('orden', 0);
        $data['icono'] = $request->input('icono', 'fas fa-university');

        if ($request->hasFile('imagen_file')) {
            $file = $request->file('imagen_file');
            $uploadPath = public_path('uploads/instalaciones');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['imagen'] = 'uploads/instalaciones/' . $filename;
        }

        Instalacion::create($data);

        return redirect(route('instalaciones-gestion.index'))->with('success', 'Instalación o equipamiento registrado correctamente.');
    }

    public function edit($id)
    {
        $instalacion = Instalacion::findOrFail($id);
        return view('instalaciones.edit', compact('instalacion'));
    }

    public function update(Request $request, $id)
    {
        $instalacion = Instalacion::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'icono' => 'nullable|string|max:100',
            'descripcion' => 'required|string',
            'caracteristicas' => 'nullable|string',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'orden' => 'nullable|integer',
            'activo' => 'required|boolean'
        ]);

        $data = $request->except(['imagen_file']);
        $data['orden'] = $request->input('orden', 0);
        $data['icono'] = $request->input('icono', 'fas fa-university');

        if ($request->hasFile('imagen_file')) {
            $file = $request->file('imagen_file');
            $uploadPath = public_path('uploads/instalaciones');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['imagen'] = 'uploads/instalaciones/' . $filename;
        }

        $instalacion->update($data);

        return redirect(route('instalaciones-gestion.index'))->with('success', 'Instalación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $instalacion = Instalacion::findOrFail($id);
        $instalacion->delete();

        return redirect(route('instalaciones-gestion.index'))->with('success', 'Instalación eliminada.');
    }
}
