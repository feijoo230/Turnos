<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StorePermiso;

class PermisosController extends Controller
{
    public function __construct()
    {
        //$this->permisosRepository = $permisoRepo;
    }

    public function index()
    {
        $permisos = Permission::all();

        return view('permisos.index')
            ->with('permisos', $permisos);
    }

    public function show($id)
    {
        $permiso = Permission::find($id);

        if (empty($permiso)) {
            return 'ERROR!';
        }

        return view('permisos.show')->with('permiso', $permiso);
    }

    public function create()
    {
        return view('permisos.create');
    }

    public function store(StorePermiso $request)
    {
        $permiso = new Permission(
            [
                'name' => $request['name']
            ]
        );
        
        $permiso->save();

        return redirect(route('permisos.index'));
    }

    public function edit($id)
    {
        $permiso = Permission::find($id);

        return view('permisos.edit')->with('permiso', $permiso);
    }

    public function update($id, StorePermiso $request)
    {
        $permiso = Permission::find($id);
        $permiso->name = $request['name'];

        $permiso->save();

        return redirect(route('permisos.index'));
    }

    public function destroy($id)
    {
        $permiso = Permission::find($id);

        if (empty($permiso)) {
            Flash::error('Permiso not found');

            return redirect(route('permisos.index'));
        }

        $permiso->delete();

        return redirect(route('permisos.index'));
    }
}
