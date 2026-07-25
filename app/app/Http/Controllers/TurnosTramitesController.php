<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Turnos_Tramites;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTurnoTramite;
use App\Models\Rol;

class TurnosTramitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = Auth::user();
        $isAdmin = $usuario->roles->contains(Rol::ADMINISTRADOR);

        if ($isAdmin) {
            $turnostramites = Turnos_Tramites::with('tramite.dependencia')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $dependencia_ids = $usuario->dependencias()->where('usuarios_dependencias.activo', true)->pluck('dependencias.id');
    
            $turnostramites = Turnos_Tramites::with('tramite.dependencia')
                ->whereHas('tramite', function ($query) use ($dependencia_ids) {
                    $query->whereIn('dependencia_id', $dependencia_ids);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('turnostramites.index')
            ->with('turnostramites', $turnostramites);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuario = Auth::user();
        $isAdmin = $usuario->roles->contains(Rol::ADMINISTRADOR);
        
        $query = DB::table('dependencias')
            ->join('dependencia_tramites', 'dependencia_tramites.dependencia_id', '=', 'dependencias.id')
            ->whereNull('dependencia_tramites.deleted_at');

        if (!$isAdmin) {
            $query->join('usuarios_dependencias', 'dependencias.id', '=', 'usuarios_dependencias.dependencia_id')
                  ->where('usuarios_dependencias.usuario_id', $usuario->id);
        }
        
        $dependenciaTramites = $query->select(DB::raw("CONCAT(dependencias.nombre, ' - ', dependencia_tramites.nombre) as nombre_completo"), 'dependencia_tramites.id')
            ->pluck('nombre_completo', 'dependencia_tramites.id')->toArray();
        
        $tiposEvento = \App\Models\TipoEvento::where('activo', true)->pluck('nombre', 'id')->toArray();
        $proyectosExtension = \App\Models\ProyectoExtension::where('activo', true)->pluck('nombre', 'id')->toArray();
        $usuarios = \App\Models\Usuario::where('activo', true)->orWhereNull('activo')->pluck('name', 'id')->toArray();
        
        return view('turnostramites.create')
            ->with('dependenciaTramites', $dependenciaTramites)
            ->with('tiposEvento', $tiposEvento)
            ->with('proyectosExtension', $proyectosExtension)
            ->with('usuarios', $usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTurnoTramite $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            
            
            $turnostramites = Turnos_Tramites::create([
                'dependencia_tramite_id' => $input['dependencia_tramite_id'],
                'fecha_desde' => $input['fecha_desde'],
                'fecha_hasta' => $input['fecha_hasta'],
                'activo' => isset($input['activo']) ? true : false,
                'tipo_evento_id' => $input['tipo_evento_id'] ?? null,
                'proyecto_extension_id' => $input['proyecto_extension_id'] ?? null,
                'responsable_id' => $input['responsable_id'] ?? null,
            ]);
            
            
            if (isset($input['horarios']) && is_array($input['horarios'])) {
                foreach ($input['horarios'] as $horario) {
                    if (!empty($horario['hora_inicio']) && !empty($horario['hora_fin'])) {
                        $turnostramites->turnosHorarios()->create([
                            'hora_inicio' => $horario['hora_inicio'],
                            'hora_fin' => $horario['hora_fin'],
                            'duracion_minutos' => $horario['duracion_minutos'],
                            'cantidad_turnos' => $horario['cantidad_turnos'],
                            'activo' => isset($horario['activo']) && $horario['activo'] == 1,
                            'lunes' => isset($horario['lunes']) && $horario['lunes'] == 1,
                            'martes' => isset($horario['martes']) && $horario['martes'] == 1,
                            'miercoles' => isset($horario['miercoles']) && $horario['miercoles'] == 1,
                            'jueves' => isset($horario['jueves']) && $horario['jueves'] == 1,
                            'viernes' => isset($horario['viernes']) && $horario['viernes'] == 1,
                            'sabado' => isset($horario['sabado']) && $horario['sabado'] == 1,
                            'domingo' => isset($horario['domingo']) && $horario['domingo'] == 1,
                        ]);
                    }
                }
            }
            
            DB::commit();
            return redirect(route('turnostramites.index'))
                ->with('success', 'Turno y horarios creados correctamente');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el turno y horarios: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $turnostramites = \App\Models\Turnos_Tramites::with(['turnosHorarios', 'tramite.dependencia', 'tipoEvento', 'proyectoExtension', 'responsable'])->find($id);

        if (empty($turnostramites)) {
            return redirect(route('turnostramites.index'));
        }

        return view('turnostramites.show')->with('turnostramite', $turnostramites);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $turnostramites = Turnos_Tramites::with('turnosHorarios')->find($id);
        $usuario = Auth::user();
        $isAdmin = $usuario->roles->contains(Rol::ADMINISTRADOR);
        
        
        $query = DB::table('dependencias')
            ->join('dependencia_tramites', 'dependencia_tramites.dependencia_id', '=', 'dependencias.id')
            ->whereNull('dependencia_tramites.deleted_at');
        
        if (!$isAdmin) {
            $query->join('usuarios_dependencias', 'dependencias.id', '=', 'usuarios_dependencias.dependencia_id')
                  ->where('usuarios_dependencias.usuario_id', $usuario->id);
        }

        $dependenciaTramites = $query->select(DB::raw("CONCAT(dependencias.nombre, ' - ', dependencia_tramites.nombre) as nombre_completo"), 'dependencia_tramites.id')
            ->pluck('nombre_completo', 'dependencia_tramites.id')
            ->toArray();
        
        if (empty($turnostramites)) {
            return redirect()->back()->with('error', 'Turno no encontrado');
        }
        
        $tiposEvento = \App\Models\TipoEvento::where('activo', true)->pluck('nombre', 'id')->toArray();
        $proyectosExtension = \App\Models\ProyectoExtension::where('activo', true)->pluck('nombre', 'id')->toArray();
        $usuarios = \App\Models\Usuario::where('activo', true)->orWhereNull('activo')->pluck('name', 'id')->toArray();
        
        return view('turnostramites.edit')
            ->with('turnostramites', $turnostramites)
            ->with('dependenciaTramites', $dependenciaTramites)
            ->with('tiposEvento', $tiposEvento)
            ->with('proyectosExtension', $proyectosExtension)
            ->with('usuarios', $usuarios);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTurnoTramite $request, $id)
    {
        DB::beginTransaction();
        try {
            $turnostramites = Turnos_Tramites::find($id);
            
            if (empty($turnostramites)) {
                return redirect()->back()->with('error', 'Turno no encontrado');
            }
            
            $turnostramites->fill($request->validated());
            $turnostramites->activo = $request->has('activo');
            $turnostramites->save();
            
            
            if (isset($request->horarios)) {
                $turnostramites->turnosHorarios()->delete();
                foreach ($request->horarios as $horario) {
                    $turnostramites->turnosHorarios()->create([
                        'hora_inicio' => $horario['hora_inicio'],
                        'hora_fin' => $horario['hora_fin'],
                        'duracion_minutos' => $horario['duracion_minutos'],
                        'cantidad_turnos' => $horario['cantidad_turnos'],
                        'activo' => isset($horario['activo']) && $horario['activo'] == 1,
                        'lunes' => isset($horario['lunes']) && $horario['lunes'] == 1,
                        'martes' => isset($horario['martes']) && $horario['martes'] == 1,
                        'miercoles' => isset($horario['miercoles']) && $horario['miercoles'] == 1,
                        'jueves' => isset($horario['jueves']) && $horario['jueves'] == 1,
                        'viernes' => isset($horario['viernes']) && $horario['viernes'] == 1,
                        'sabado' => isset($horario['sabado']) && $horario['sabado'] == 1,
                        'domingo' => isset($horario['domingo']) && $horario['domingo'] == 1,
                    ]);
                }
            }
            
            DB::commit();
            return redirect(route('turnostramites.index'))
                ->with('success', 'Turno y horarios actualizados correctamente');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el turno y horarios: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $turnostramites = Turnos_Tramites::find($id);

            if (empty($turnostramites)) {
                return redirect(route('turnostramites.index'))
                    ->with('error', 'Turno no encontrado');
            }

            
            if ($turnostramites->reservas()->count() > 0) {
                return redirect(route('turnostramites.index'))
                    ->with('error', 'No se puede eliminar el turno porque tiene reservas asociadas');
            }

            
            $turnostramites->turnosHorarios()->delete();
            
            
            $turnostramites->delete();

            DB::commit();
            return redirect(route('turnostramites.index'))
                ->with('success', 'Turno eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('turnostramites.index'))
                ->with('error', 'Error al eliminar el turno: ' . $e->getMessage());
        }
    }
}
