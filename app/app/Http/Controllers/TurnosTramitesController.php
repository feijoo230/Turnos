<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Turnos_Tramites;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTurnoTramite;

class TurnosTramitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario_id = Auth::id();
        
        $turnostramites = Turnos_Tramites::select('turnos_tramites.*')
            ->join('dependencia_tramites', 'turnos_tramites.dependencia_tramite_id', '=', 'dependencia_tramites.id')
            ->join('usuarios_dependencias', 'dependencia_tramites.dependencia_id', '=', 'usuarios_dependencias.dependencia_id')
            ->where('usuarios_dependencias.usuario_id', $usuario_id)
            ->where('usuarios_dependencias.activo', true)
            ->orderBy('turnos_tramites.created_at', 'desc')
            ->paginate(15);

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
        $usuario_id = Auth::id();
        
        $dependenciaTramites = DB::table('dependencias')
        ->join('dependencia_tramites', 'dependencia_tramites.dependencia_id', '=', 'dependencias.id')
        ->join('usuarios_dependencias', 'dependencias.id', '=', 'usuarios_dependencias.dependencia_id')
        ->where('usuarios_dependencias.usuario_id', $usuario_id)
        ->select(DB::raw("CONCAT(dependencias.nombre, ' - ', dependencia_tramites.nombre) as nombre_completo"), 'dependencia_tramites.id')
        ->pluck('nombre_completo', 'dependencia_tramites.id')->toArray();
        
        
        return view('turnostramites.create')
            ->with('dependenciaTramites', $dependenciaTramites);
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
            ]);
            
            
            if (isset($input['horarios']) && is_array($input['horarios'])) {
                foreach ($input['horarios'] as $horario) {
                    if (!empty($horario['hora_inicio']) && !empty($horario['hora_fin'])) {
                        $turnostramites->turnosHorarios()->create([
                            'hora_inicio' => $horario['hora_inicio'],
                            'hora_fin' => $horario['hora_fin'],
                            'duracion_minutos' => $horario['duracion_minutos'],
                            'activo' => isset($horario['activo']) && $horario['activo'] == 1,
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
        //
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
        $usuario_id = Auth::id();
        
        
        $dependenciaTramites = DB::table('dependencias')
            ->join('dependencia_tramites', 'dependencia_tramites.dependencia_id', '=', 'dependencias.id')
            ->join('usuarios_dependencias', 'dependencias.id', '=', 'usuarios_dependencias.dependencia_id')
            ->where('usuarios_dependencias.usuario_id', $usuario_id)
            ->select(DB::raw("CONCAT(dependencias.nombre, ' - ', dependencia_tramites.nombre) as nombre_completo"), 'dependencia_tramites.id')
            ->pluck('nombre_completo', 'dependencia_tramites.id')
            ->toArray();
        
        if (empty($turnostramites)) {
            return redirect()->back()->with('error', 'Turno no encontrado');
        }
        
        return view('turnostramites.edit')
            ->with('turnostramites', $turnostramites)
            ->with('dependenciaTramites', $dependenciaTramites);
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
                        'activo' => isset($horario['activo']) && $horario['activo'] == 1
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
