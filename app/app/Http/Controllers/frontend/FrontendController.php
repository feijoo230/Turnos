<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProyectoExtension;
use App\Models\Investigacion;
use App\Models\MiembroEquipo;
use App\Models\Instalacion;

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
        $instalaciones = Instalacion::where('activo', true)
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('frontend.observatorio.instalaciones', compact('instalaciones'));
    }

    public function equipo()
    {
        $responsables = MiembroEquipo::with('user')
            ->where('activo', true)
            ->where('tipo', 'responsable')
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $colaboradores = MiembroEquipo::with('user')
            ->where('activo', true)
            ->where('tipo', 'colaborador')
            ->orderBy('orden', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('frontend.observatorio.equipo', compact('responsables', 'colaboradores'));
    }
}
