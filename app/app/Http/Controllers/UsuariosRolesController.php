<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Flash;
use App\User;

class UsuariosRolesController extends Controller
{
    public function index($id_usuario = 0)
    {
        $roles = DB::table('roles')->get();
        
        $user = User::find($id_usuario);
        $roles_usuarios = $user->roles;
        
        $aRoles = array();
        foreach ($roles as $value) {
            $aAux = array();
            $aAux['name'] = $value->name;
            $aAux['tiene_rol'] = false;
            $aRoles[$value->id] = $aAux;
        }

        foreach ($roles_usuarios as $value) {
            $aAux = &$aRoles[$value->id];
            $aAux['tiene_rol'] = true;
        }
        
        return view('usuariosroles.index')
            ->with('id_usuario', $id_usuario)
            ->with('aRoles', $aRoles);
    }

    public function guardar(Request $request)
    {
        $input = $request->all();

        $user = User::find($input['id_usuario']);

        $user->roles()->sync($request->id_roles);  

        Flash::success('Los roles se guardaron exitosamente.');

        return redirect(route('usuarios.index'));
    }
}
