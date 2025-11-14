<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class RolesPermisosController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        //$this->permisoRepository->pushCriteria(new RequestCriteria($request));
        $permisos = Permission::get();
        $roles = Role::all()->pluck('name', 'id');
        $role_id = $request->input('role_id');
        
        if (!isset($role_id)) {
            //Se selecciona el Rol Admin por defecto, pero no se deberia fijar una constante
            // osea el valor 1 (uno)
            $role_id = config('constants.ROLE_ID_MODULO_ROLES_PERMISO', 'Laravel');
        }

        $rol = Role::find($role_id);
        $rol_permisos = $rol->permissions()->get()->toArray();

        $aPermisos = array();
        foreach ($permisos as $value) {
            $aAux = array();
            $aAux['name'] = $value->name;
            $aAux['guard_name'] = $value->guard_name;
            $aAux['tiene_permiso'] = false;
            $aPermisos[$value->id] = $aAux;
        }

        foreach ($rol_permisos as $value) {
            $aAux = &$aPermisos[$value['id']];
            $aAux['tiene_permiso'] = true;
        }
        
        return view('rolespermisos.index')
            ->with('permisos', $permisos)
            ->with('roles', $roles)
            ->with('role_id', $role_id)
            ->with('rol_permisos', $aPermisos);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $role = Role::find($input['id_role']);
        $permissions = isset($input['id_permisos']) ? $input['id_permisos'] : [];
        $role->syncPermissions($permissions);

        return redirect(route('rolespermisos.index', array('role_id' => $input['id_role'])));
    }
}
