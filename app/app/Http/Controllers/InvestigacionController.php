<?php

namespace App\Http\Controllers;

use App\Models\Investigacion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvestigacionController extends Controller
{
    public function index()
    {
        $investigaciones = Investigacion::orderBy('orden', 'asc')
            ->orderBy('ano', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('investigaciones.index', compact('investigaciones'));
    }

    public function create()
    {
        return view('investigaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'revista' => 'nullable|string|max:255',
            'autores' => 'nullable|string|max:255',
            'ano' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'enlace_url' => 'nullable|url|max:255',
            'archivo_pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'orden' => 'nullable|integer',
            'activo' => 'required|boolean'
        ]);

        $data = $request->except(['archivo_pdf_file']);
        $data['orden'] = $request->input('orden', 0);

        if ($request->hasFile('archivo_pdf_file')) {
            $file = $request->file('archivo_pdf_file');
            $uploadPath = public_path('uploads/investigaciones');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['archivo_pdf'] = 'uploads/investigaciones/' . $filename;
        }

        Investigacion::create($data);

        return redirect(route('investigaciones.index'))->with('success', 'Publicación de investigación guardada correctamente.');
    }

    public function edit($id)
    {
        $investigacion = Investigacion::findOrFail($id);
        return view('investigaciones.edit', compact('investigacion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'revista' => 'nullable|string|max:255',
            'autores' => 'nullable|string|max:255',
            'ano' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'enlace_url' => 'nullable|url|max:255',
            'archivo_pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'orden' => 'nullable|integer',
            'activo' => 'required|boolean'
        ]);

        $investigacion = Investigacion::findOrFail($id);
        $data = $request->except(['archivo_pdf_file']);
        $data['orden'] = $request->input('orden', 0);

        if ($request->hasFile('archivo_pdf_file')) {
            $file = $request->file('archivo_pdf_file');
            $uploadPath = public_path('uploads/investigaciones');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            // Eliminar PDF anterior si existía en uploads/
            if (!empty($investigacion->archivo_pdf) && Str::startsWith($investigacion->archivo_pdf, 'uploads/investigaciones/')) {
                $oldPath = public_path($investigacion->archivo_pdf);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['archivo_pdf'] = 'uploads/investigaciones/' . $filename;
        }

        $investigacion->update($data);

        return redirect(route('investigaciones.index'))->with('success', 'Publicación de investigación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $investigacion = Investigacion::findOrFail($id);

        if (!empty($investigacion->archivo_pdf) && Str::startsWith($investigacion->archivo_pdf, 'uploads/investigaciones/')) {
            $oldPath = public_path($investigacion->archivo_pdf);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $investigacion->delete();

        return redirect(route('investigaciones.index'))->with('success', 'Publicación de investigación eliminada correctamente.');
    }
}
