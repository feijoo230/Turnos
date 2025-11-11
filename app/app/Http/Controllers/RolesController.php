<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreRol;


class RolesController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $roles = Role::all();

        return view('roles.index')
            ->with('roles', $roles);
    }

    public function show($id)
    {
        $rol = Role::find($id);

        if (empty($rol)) {
            return 'ERROR!';
        }

        return view('roles.show')->with('rol', $rol);
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(StoreRol $request)
    {
        $rol = new Role(
            [
                'name' => $request['name']
            ]
        );
        
        $rol->save();

        return redirect(route('roles.index'));
    }

    public function edit($id)
    {
        $rol = Role::find($id);

        return view('roles.edit')->with('rol', $rol);
    }

    public function update($id, StoreRol $request)
    {
        $rol = Role::find($id);
        $rol->name = $request['name'];

        $rol->save();

        return redirect(route('roles.index'));
    }

    public function destroy($id)
    {
        $rol = Role::find($id);

        if (empty($rol)) {
            Flash::error('Permiso not found');

            return redirect(route('roles.index'));
        }

        $rol->delete();

        return redirect(route('roles.index'));
    }
}
