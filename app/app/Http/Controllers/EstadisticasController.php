<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turnos_Dependencias_Reservas;
use App\Models\Dependencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadisticasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dependencias = Dependencia::orderBy('nombre', 'asc')->pluck('nombre', 'id');
        $dependenciaDefault = 27; // Observatorio Astronómico
        return view('estadisticas.index', compact('dependencias', 'dependenciaDefault'));
    }

    public function getData(Request $request)
    {
        $dependenciaId = $request->input('dependencia_id', 27);
        $periodo = $request->input('periodo', 'historico');

        // Construir query base
        $baseQuery = Turnos_Dependencias_Reservas::query()
            ->join('dependencia_tramites', 'dependencia_turnos_reservas.dependencia_tramite_id', '=', 'dependencia_tramites.id')
            ->join('dependencias', 'dependencia_tramites.dependencia_id', '=', 'dependencias.id');

        if ($dependenciaId !== 'todas') {
            $baseQuery->where('dependencias.id', $dependenciaId);
        }

        if ($periodo === '30d') {
            $baseQuery->where('dependencia_turnos_reservas.fecha', '>=', now()->subDays(30));
        } elseif ($periodo === '90d') {
            $baseQuery->where('dependencia_turnos_reservas.fecha', '>=', now()->subDays(90));
        } elseif ($periodo === 'ano') {
            $baseQuery->whereYear('dependencia_turnos_reservas.fecha', now()->year);
        }

        // --- 1. KPIs Generales adaptados al Observatorio ---
        $kpiQuery = clone $baseQuery;
        $totalTurnos = (int) $kpiQuery->count();
        
        $totalVisitantes = (int) (clone $baseQuery)->sum(
            DB::raw('CASE WHEN cantidad_personas > 0 THEN cantidad_personas ELSE 1 END')
        );

        $totalDelegaciones = (int) (clone $baseQuery)
            ->where(function($q) {
                $q->where('es_grupal', 1)->orWhereNotNull('nombre_institucion');
            })->count();

        $totalAtendidos = (int) (clone $baseQuery)->where('estado_id', 3)->count();
        $totalCancelados = (int) (clone $baseQuery)->where('estado_id', 4)->count();
        $totalPendientes = (int) (clone $baseQuery)->whereIn('estado_id', [1, 2])->count();

        $denominador = $totalAtendidos + $totalCancelados;
        $tasaEfectividad = $denominador > 0 ? round(($totalAtendidos / $denominador) * 100, 1) : ($totalTurnos > 0 ? 100 : 0);

        // --- 2. Turnos por Estado ---
        $estadosData = (clone $baseQuery)
            ->select('estado_id', DB::raw('count(*) as total'))
            ->groupBy('estado_id')
            ->get();

        $nombresEstados = [
            1 => 'Pendiente',
            2 => 'Confirmado',
            3 => 'Atendido',
            4 => 'Cancelado'
        ];
        $labelsEstado = [];
        $valuesEstado = [];
        foreach ($estadosData as $item) {
            $labelsEstado[] = $nombresEstados[$item->estado_id] ?? 'Estado ' . $item->estado_id;
            $valuesEstado[] = $item->total;
        }

        // --- 3. Distribución por Modalidad de Visita (Observatorio) ---
        $reservasModalidad = (clone $baseQuery)
            ->select('nombre_institucion', 'es_grupal', 'dependencia_tramites.tipo_modalidad', 'cantidad_personas')
            ->get();

        $conteoModalidades = [
            'Escolar / Institucional' => 0,
            'Grupal' => 0,
            'Individual' => 0,
            'Eventos Especiales / Mixta' => 0
        ];

        foreach ($reservasModalidad as $r) {
            if (!empty($r->nombre_institucion) || $r->tipo_modalidad === 'institucional') {
                $conteoModalidades['Escolar / Institucional']++;
            } elseif ($r->es_grupal && $r->tipo_modalidad !== 'mixto') {
                $conteoModalidades['Grupal']++;
            } elseif ($r->tipo_modalidad === 'mixto') {
                $conteoModalidades['Eventos Especiales / Mixta']++;
            } else {
                $conteoModalidades['Individual']++;
            }
        }

        // --- 4. Top Actividades / Trámites Astronómicos ---
        $porTramite = (clone $baseQuery)
            ->select('dependencia_tramites.nombre', DB::raw('count(*) as total'), DB::raw('SUM(CASE WHEN cantidad_personas > 0 THEN cantidad_personas ELSE 1 END) as total_personas'))
            ->groupBy('dependencia_tramites.nombre')
            ->orderBy('total', 'desc')
            ->take(8)
            ->get();

        $labelsTramite = $porTramite->pluck('nombre');
        $dataTramite = $porTramite->pluck('total');
        $personasTramite = $porTramite->pluck('total_personas');

        // --- 5. Ranking de Instituciones Educativas y Colegios ---
        $porInstitucion = (clone $baseQuery)
            ->whereNotNull('nombre_institucion')
            ->where('nombre_institucion', '!=', '')
            ->select('nombre_institucion', DB::raw('count(*) as total_turnos'), DB::raw('SUM(CASE WHEN cantidad_personas > 0 THEN cantidad_personas ELSE 1 END) as total_estudiantes'))
            ->groupBy('nombre_institucion')
            ->orderBy('total_estudiantes', 'desc')
            ->take(6)
            ->get();

        $labelsInstitucion = $porInstitucion->pluck('nombre_institucion');
        $dataInstitucion = $porInstitucion->pluck('total_estudiantes');

        // --- 6. Franja Horaria de Observación (Nocturna vs Diurna) ---
        $porHorario = (clone $baseQuery)
            ->select(
                DB::raw("CASE WHEN CAST(hora AS TIME) >= '18:30:00' THEN 'Observación Nocturna (Cielo Profundo)' ELSE 'Visita Diurna / Charlas' END as franja"),
                DB::raw('count(*) as total')
            )
            ->groupBy('franja')
            ->get();

        $labelsHorario = $porHorario->pluck('franja');
        $dataHorario = $porHorario->pluck('total');

        // --- 7. Evolución Temporal (Turnos y Visitantes) ---
        $evolucion = (clone $baseQuery)
            ->select(
                DB::raw('DATE(fecha) as fecha_reserva'),
                DB::raw('count(*) as total_turnos'),
                DB::raw('SUM(CASE WHEN cantidad_personas > 0 THEN cantidad_personas ELSE 1 END) as total_personas')
            )
            ->groupBy('fecha_reserva')
            ->orderBy('fecha_reserva', 'asc')
            ->take(30)
            ->get();

        $labelsEvolucion = [];
        $dataEvolucionTurnos = [];
        $dataEvolucionPersonas = [];
        foreach ($evolucion as $e) {
            $labelsEvolucion[] = Carbon::parse($e->fecha_reserva)->format('d/m/Y');
            $dataEvolucionTurnos[] = $e->total_turnos;
            $dataEvolucionPersonas[] = $e->total_personas;
        }

        return response()->json([
            'kpis' => [
                'total_turnos' => $totalTurnos,
                'total_visitantes' => $totalVisitantes,
                'total_delegaciones' => $totalDelegaciones,
                'total_atendidos' => $totalAtendidos,
                'total_cancelados' => $totalCancelados,
                'total_pendientes' => $totalPendientes,
                'tasa_efectividad' => $tasaEfectividad
            ],
            'estado' => [
                'labels' => $labelsEstado,
                'data' => $valuesEstado
            ],
            'modalidad' => [
                'labels' => array_keys($conteoModalidades),
                'data' => array_values($conteoModalidades)
            ],
            'tramite' => [
                'labels' => $labelsTramite,
                'data' => $dataTramite,
                'personas' => $personasTramite
            ],
            'instituciones' => [
                'labels' => $labelsInstitucion,
                'data' => $dataInstitucion
            ],
            'horario' => [
                'labels' => $labelsHorario,
                'data' => $dataHorario
            ],
            'evolucion' => [
                'labels' => $labelsEvolucion,
                'turnos' => $dataEvolucionTurnos,
                'personas' => $dataEvolucionPersonas
            ]
        ]);
    }
}
