<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turnos_Tramites;

class TurnosHorariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($turnoTramiteId)
    {
        // Find the TurnoTramite by ID
        $turnoTramite = Turnos_Tramites::find($turnoTramiteId);

        // Check if TurnoTramite exists
        if (!$turnoTramite) {
            return redirect()->back()->with('error', 'TurnoTramite not found.');
        }

        // Get all related TurnosHorarios
        $turnosHorarios = $turnoTramite->turnosHorarios()->paginate(15);

        // Pass the data to the view
        return view('turnoshorarios.index', compact('turnoTramite', 'turnosHorarios'));
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
