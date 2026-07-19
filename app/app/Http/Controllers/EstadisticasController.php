<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turnos_Dependencias_Reservas;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('estadisticas.index');
    }

    public function getData(Request $request)
    {
        // 1. Turnos por Estado
        $porEstado = Turnos_Dependencias_Reservas::select('estado_id', DB::raw('count(*) as total'))
            ->groupBy('estado_id')
            ->get();
        
        $nombresEstados = [
            1 => 'Pendiente',
            2 => 'Confirmado / Asignado',
            3 => 'Atendido',
            4 => 'Cancelado'
        ];

        $labelsEstado = [];
        $dataEstado = [];
        foreach($porEstado as $item) {
            $labelsEstado[] = $nombresEstados[$item->estado_id] ?? 'Estado '.$item->estado_id;
            $dataEstado[] = $item->total;
        }

        // 2. Turnos por Trámite
        $porTramite = Turnos_Dependencias_Reservas::join('dependencia_tramites', 'dependencia_turnos_reservas.dependencia_tramite_id', '=', 'dependencia_tramites.id')
            ->select('dependencia_tramites.nombre', DB::raw('count(*) as total'))
            ->groupBy('dependencia_tramites.nombre')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        $labelsTramite = $porTramite->pluck('nombre');
        $dataTramite = $porTramite->pluck('total');

        // 3. Turnos por Dependencia
        $porDependencia = Turnos_Dependencias_Reservas::join('dependencia_tramites', 'dependencia_turnos_reservas.dependencia_tramite_id', '=', 'dependencia_tramites.id')
            ->join('dependencias', 'dependencia_tramites.dependencia_id', '=', 'dependencias.id')
            ->select('dependencias.nombre', DB::raw('count(*) as total'))
            ->groupBy('dependencias.nombre')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        $labelsDependencia = $porDependencia->pluck('nombre');
        $dataDependencia = $porDependencia->pluck('total');

        // 4. Evolución Temporal (Últimos 30 días)
        $evolucion = Turnos_Dependencias_Reservas::select(DB::raw('DATE(fecha) as fecha_reserva'), DB::raw('count(*) as total'))
            ->where('fecha', '>=', now()->subDays(30))
            ->groupBy('fecha_reserva')
            ->orderBy('fecha_reserva', 'asc')
            ->get();

        $labelsEvolucion = $evolucion->pluck('fecha_reserva');
        $dataEvolucion = $evolucion->pluck('total');

        return response()->json([
            'estado' => [
                'labels' => $labelsEstado,
                'data' => $dataEstado
            ],
            'tramite' => [
                'labels' => $labelsTramite,
                'data' => $dataTramite
            ],
            'dependencia' => [
                'labels' => $labelsDependencia,
                'data' => $dataDependencia
            ],
            'evolucion' => [
                'labels' => $labelsEvolucion,
                'data' => $dataEvolucion
            ]
        ]);
    }
}
