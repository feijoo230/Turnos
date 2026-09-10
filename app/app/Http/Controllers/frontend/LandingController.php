<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dependencia;

class LandingController extends Controller
{
    /**
     * Muestra la Landing Page oficial del Observatorio Astronómico Dr. Elvio Alanís.
     */
    public function index()
    {
        // Se puede consultar si hay dependencias activas o estadísticas si se desea
        $dependencias = Dependencia::getDependenciasConTurnos();
        
        return view('frontend.landing', compact('dependencias'));
    }
}
