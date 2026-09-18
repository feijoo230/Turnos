<?php

namespace App\Http\Controllers;

use App\Models\ProyectoExtension;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProyectoExtensionController extends Controller
{
    public function index()
    {
        $proyectos = ProyectoExtension::orderBy('orden', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15);

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
            'subtitulo' => 'nullable|string|max:255',
            'ano' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'enlace_url' => 'nullable|url|max:255',
            'orden' => 'nullable|integer',
            'activo' => 'required|boolean'
        ]);

        $data = $request->except(['imagen_file']);
        $data['orden'] = $request->input('orden', 0);

        if ($request->hasFile('imagen_file')) {
            $file = $request->file('imagen_file');
            $uploadPath = public_path('uploads/proyectos');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['imagen'] = 'uploads/proyectos/' . $filename;
        }

        ProyectoExtension::create($data);

        return redirect(route('proyectos-extension.index'))->with('success', 'Proyecto de extensión guardado correctamente.');
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
            'subtitulo' => 'nullable|string|max:255',
            'ano' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'enlace_url' => 'nullable|url|max:255',
            'orden' => 'nullable|integer',
            'activo' => 'required|boolean'
        ]);

        $proyecto = ProyectoExtension::findOrFail($id);
        $data = $request->except(['imagen_file']);
        $data['orden'] = $request->input('orden', 0);

        if ($request->hasFile('imagen_file')) {
            $file = $request->file('imagen_file');
            $uploadPath = public_path('uploads/proyectos');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            // Eliminar imagen anterior si era un archivo subido en uploads/
            if (!empty($proyecto->imagen) && Str::startsWith($proyecto->imagen, 'uploads/proyectos/')) {
                $oldPath = public_path($proyecto->imagen);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['imagen'] = 'uploads/proyectos/' . $filename;
        }

        $proyecto->update($data);

        return redirect(route('proyectos-extension.index'))->with('success', 'Proyecto de extensión actualizado correctamente.');
    }

    public function destroy($id)
    {
        $proyecto = ProyectoExtension::findOrFail($id);

        if ($proyecto->turnos_tramites()->count() > 0) {
            return redirect(route('proyectos-extension.index'))->with('error', 'No se puede eliminar porque tiene turnos de atención asociados.');
        }

        if (!empty($proyecto->imagen) && Str::startsWith($proyecto->imagen, 'uploads/proyectos/')) {
            $oldPath = public_path($proyecto->imagen);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $proyecto->delete();

        return redirect(route('proyectos-extension.index'))->with('success', 'Proyecto de extensión eliminado correctamente.');
    }
}
