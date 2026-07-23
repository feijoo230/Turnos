<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turnos_Dependencias_Reservas;
use App\Models\ProyectoExtension;
use App\Models\TipoEvento;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->hasRole('ADMINISTRADOR') && !auth()->user()->hasRole('OPERADOR')) {
            return redirect('/');
        }

        $hoy = Carbon::today()->format('Y-m-d');
        
        $turnosHoy = Turnos_Dependencias_Reservas::whereDate('fecha', $hoy)->count();
        $turnosPendientes = Turnos_Dependencias_Reservas::where('activo', 1)->whereDate('fecha', '>=', $hoy)->count();
        $totalProyectos = ProyectoExtension::where('activo', 1)->count();
        $totalEventos = TipoEvento::where('activo', 1)->count();

        $proximasReservas = Turnos_Dependencias_Reservas::with(['turno_horario.turno_tramite.tramite.dependencia'])
            ->where('activo', 1)
            ->whereDate('fecha', '>=', $hoy)
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->take(6)
            ->get();

        return view('home', compact('turnosHoy', 'turnosPendientes', 'totalProyectos', 'totalEventos', 'proximasReservas'));
    }
}
