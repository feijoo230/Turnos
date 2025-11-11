<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Flash;
use App\User;
use App\Models\Dependencia;
use App\Models\Usuario;

class UsuariosDependenciasController extends Controller
{
    public function index($id_usuario = 0)
    {
        $user = Usuario::find($id_usuario);

        $dependencias = Dependencia::defaultOrder()->get()
            ->toTree();
        $result = array();
        Dependencia::tableTreeWithNivel($dependencias, '', $result);
        $dependencias = $result;

        
        
        $dependencias_usuarios = $user->dependencias;
        
        $aRoles = array();
        foreach ($dependencias as $value) {
            $aAux = array();
            $aAux['nombre'] = $value['nombre'];
            $aAux['tiene_dependencia'] = false;
            $aRoles[$value['id']] = $aAux;
        }

        foreach ($dependencias_usuarios as $value) {
            $aAux = &$aRoles[$value->id];
            $aAux['tiene_dependencia'] = true;
        }
        
        return view('usuariosdependencias.index')
            ->with('id_usuario', $id_usuario)
            ->with('aRoles', $aRoles);
    }

    public function guardar(Request $request)
    {
        $input = $request->all();

        $user = Usuario::find($input['id_usuario']);

        $user->dependencias()->sync($request->id_dependencias);  

        Flash::success('Los roles se guardaron exitosamente.');

        return redirect(route('usuarios.index'));
    }
}
