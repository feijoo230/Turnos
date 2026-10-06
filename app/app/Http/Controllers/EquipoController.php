<?php

namespace App\Http\Controllers;

use App\Models\MiembroEquipo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->input('tipo', 'todos');

        $query = MiembroEquipo::query();

        if ($tipo !== 'todos' && in_array($tipo, ['responsable', 'colaborador'])) {
            $query->where('tipo', $tipo);
        }

        $miembros = $query->orderBy('tipo', 'desc')
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(15);

        return view('equipo.index', compact('miembros', 'tipo'));
    }

    public function create()
    {
        $users = User::orderBy('name', 'asc')->pluck('name', 'id')->prepend('--- Ninguno (Integrante externo / Sin usuario de acceso) ---', '');
        return view('equipo.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'tipo' => 'required|in:responsable,colaborador',
            'area' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'user_id' => 'nullable|exists:users,id',
            'biografia' => 'nullable|string',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'orden' => 'nullable|integer',
            'activo' => 'required|boolean'
        ]);

        $data = $request->except(['foto_file']);
        $data['orden'] = $request->input('orden', 0);
        $data['user_id'] = $request->filled('user_id') ? $request->input('user_id') : null;

        if ($request->hasFile('foto_file')) {
            $file = $request->file('foto_file');
            $uploadPath = public_path('uploads/equipo');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['foto'] = 'uploads/equipo/' . $filename;
        }

        MiembroEquipo::create($data);

        return redirect(route('equipo-trabajo.index'))->with('success', 'Integrante del equipo registrado correctamente.');
    }

    public function edit($id)
    {
        $miembro = MiembroEquipo::findOrFail($id);
        $users = User::orderBy('name', 'asc')->pluck('name', 'id')->prepend('--- Ninguno (Integrante externo / Sin usuario de acceso) ---', '');
        return view('equipo.edit', compact('miembro', 'users'));
    }

    public function update(Request $request, $id)
    {
        $miembro = MiembroEquipo::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'cargo' => 'nullable|string|max:255',
            'tipo' => 'required|in:responsable,colaborador',
            'area' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'user_id' => 'nullable|exists:users,id',
            'biografia' => 'nullable|string',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'orden' => 'nullable|integer',
            'activo' => 'required|boolean'
        ]);

        $data = $request->except(['foto_file']);
        $data['orden'] = $request->input('orden', 0);
        $data['user_id'] = $request->filled('user_id') ? $request->input('user_id') : null;

        if ($request->hasFile('foto_file')) {
            $file = $request->file('foto_file');
            $uploadPath = public_path('uploads/equipo');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['foto'] = 'uploads/equipo/' . $filename;
        }

        $miembro->update($data);

        return redirect(route('equipo-trabajo.index'))->with('success', 'Integrante del equipo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $miembro = MiembroEquipo::findOrFail($id);
        $miembro->delete();

        return redirect(route('equipo-trabajo.index'))->with('success', 'Integrante eliminado del equipo.');
    }
}
