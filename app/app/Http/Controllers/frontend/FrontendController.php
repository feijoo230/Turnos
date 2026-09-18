<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProyectoExtension;
use App\Models\Investigacion;

class FrontendController extends Controller
{
    public function historia()
    {
        return view('frontend.observatorio.historia');
    }

    public function proyectos()
    {
        $proyectos = ProyectoExtension::where('activo', true)
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('frontend.observatorio.proyectos', compact('proyectos'));
    }

    public function investigacion()
    {
        $investigaciones = Investigacion::where('activo', true)
            ->orderBy('orden', 'asc')
            ->orderBy('ano', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('frontend.observatorio.investigacion', compact('investigaciones'));
    }

    public function instalaciones()
    {
        return view('frontend.observatorio.instalaciones');
    }

    public function equipo()
    {
        return view('frontend.observatorio.equipo');
    }
}
