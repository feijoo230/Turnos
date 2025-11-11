<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UpdateUsuario;
use App\Http\Requests\StoreUsuario;
use App\Http\Requests\UpdateUsuarioPassword;
use App\Models\Dependencia;
use Mail;
use App\Mail\NuevoUsuario;
use App\Mail\EditarUsuario;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $usuarios = User::orderBy('name', 'asc')
            ->paginate(15);
            // ->get();

        return view('usuarios.index')->with(compact('usuarios'));
    }

    public function show($id)
    {
        $usuario = User::find($id);

        if (!empty($usuario)) {
            return view('usuarios.show')->with(compact('usuario'));
            
        } else {
            return redirect(route('usuarios.index'))->with('error', 'Error al visualizar usuario'); 
        }

    }

    public function create()
    {
        $roles = Role::all()->pluck('name', 'id')->toArray();

        $dependencias = Dependencia::defaultOrder()->get()
            ->toTree();

        $result = array();
        Dependencia::selectTree($dependencias, '', $result);
        $dependencias = $result;

        return view('usuarios.create')->with(compact('dependencias', 'roles'));
    }

    public function store(StoreUsuario $request)
    {
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        try{
            $usuario = User::create($input);
            $usuario->roles()->sync($input['role_id']);
            
            $datos = $usuario->toArray();

            Mail::to($usuario->email)
            ->send(new NuevoUsuario($datos));

            return redirect(route('usuarios.index'))->with('success','El usuario se registro correctamente.');
        } catch (\Exception $e) {
            return redirect(route('usuarios.index'))->with('error','Hubo un error en el registro del usuario. Por favor intente nuevamente.');
        }
    }

    public function edit($id)
    {
        $usuario = User::find($id);
        
        return view('usuarios.edit')
            ->with(compact('usuario'));            
    }

    public function update($id, UpdateUsuario $request)
    {
        $usuario = User::find($id);
        $usuario->name = $request['name'];
        $usuario->email = $request['email'];
        $usuario->activo = $request['activo'];
        
        try{
            $usuario->save();
            Mail::to($usuario->email)
            ->send(new EditarUsuario($request->all()));
            return redirect(route('usuarios.index'))->with('success','El usuario de actualizó correctamente.');
        } catch (\Exception $e) {
            return redirect(route('usuarios.index'))->with('error','Hubo un error en la actualización del usuario. Por favor intente nuevamente.');
        }
    }

    public function cambiarPassword($id)
    {
        $usuario = User::find($id);
        
        return view('usuarios.cambiar_password')
            ->with(compact('usuario')); 
    }

    public function storePassword(UpdateUsuarioPassword $request)
    {
        $input = $request->toArray();
        $usuario = User::find($input['id']);
        $usuario->password = bcrypt($input['password']);
              
        try{
            $usuario->save();
            
            return redirect(route('usuarios.index'))->with('success','El usuario de actualizó correctamente.');
        } catch (\Exception $e) {
            return redirect(url('usuarios.mi_perfil'))->with('error','Hubo un error en la actualización del usuario. Por favor intente nuevamente.');
        }
    }

    public function destroy($id)
    {
        $users = User::find($id);

        if (empty($users)) {
            Flash::error('Permiso not found');

            return redirect(route('usuarios.index'));
        }

        try{
            $users->delete();
            return redirect(route('usuarios.index'))->with('success','El usuario se elimino correctamente.');
        } catch (\Exception $e) {
            return redirect(route('usuarios.index'))->with('error','Hubo un error en la eliminación del usuario. Por favor intente nuevamente.');
        }

        return redirect(route('usuarios.index'));
    }

    public function mi_perfil()
    {
        $usuario = Auth::user();
       
        return view('usuarios.mi_perfil')
            ->with(compact('usuario'));
    }

    public function update_perfil(UpdateUsuario $request)
    {
        $datos = $request->toArray();
        $usuario = User::find($datos['id']);
        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];  
              
        try{
            $usuario->save();
            Mail::to($usuario->email)
            ->send(new EditarUsuario($datos));
            return redirect(url('usuarios.mi_perfil'))->with('success','El usuario de actualizó correctamente.');
        } catch (\Exception $e) {
            return redirect(url('usuarios.mi_perfil'))->with('error','Hubo un error en la actualización del usuario. Por favor intente nuevamente.');
        }
    }

    public function buscar(Request $request){

        $input = $request->all();
        
        if (isset($input['text_buscar']))
            $text_buscar = $input['text_buscar'];
        else
            $text_buscar='';

        $usuarios = User::orderBy('name', 'asc')
            ->where('name', 'like', "%{$text_buscar}%")
            ->orWhere('email', 'like', "%{$text_buscar}%")
            ->paginate(15);
        
        return view('usuarios.index')->with(compact('usuarios', 'input'));
    }

}
