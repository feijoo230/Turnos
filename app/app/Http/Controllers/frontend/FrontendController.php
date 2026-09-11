<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{
    public function historia()
    {
        return view('frontend.observatorio.historia');
    }

    public function proyectos()
    {
        return view('frontend.observatorio.proyectos');
    }

    public function investigacion()
    {
        return view('frontend.observatorio.investigacion');
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
