<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dependencia;
use App\Models\ProyectoExtension;
use App\Models\Investigacion;

class LandingController extends Controller
{
    /**
     * Muestra la Landing Page oficial del Observatorio Astronómico Dr. Elvio Alanís.
     */
    public function index()
    {
        $dependencias = Dependencia::getDependenciasConTurnos();
        
        $ultimosProyectos = ProyectoExtension::where('activo', true)
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        $ultimasInvestigaciones = Investigacion::where('activo', true)
            ->orderBy('orden', 'asc')
            ->orderBy('ano', 'desc')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();
        
        return view('frontend.landing', compact('dependencias', 'ultimosProyectos', 'ultimasInvestigaciones'));
    }
}
