<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Dependencia;
use App\Models\Turnos_Dependencias;
use App\Models\Turnos_Tramites;
use App\Models\Turnos_Dependencias_Reservas;
use App\Models\Tramite;
use App\Models\Documento;
use App\Models\Feriado;
use App\Models\Dependencia_Tramite;
use App\Http\Requests\SearchTurno;
use App\Http\Requests\StoreTurno;
use App\Mail\NuevoTramite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TramitesController extends Controller
{
    public function __construct()
    {

    }

    protected function index(Request $request)
    {
        //Se obtiene las dependencias que tienen emiten turnos
        $dependencias = Dependencia::getDependenciasConTurnos();

        //dd($dependencias);

        $aux_dependencia_id = $request->session()->get('dependencia_id');
        
        if (isset($aux_dependencia_id)) {
            $dependencia_id = $request->session()->get('dependencia_id');
        } else {
            $dependencia_id = 0;
        }

        $aux_dependencia_tramite_id = $request->session()->get('dependencia_tramite_id');
        if (isset($aux_dependencia_tramite_id)) {
            $dependencia_tramite_id = $request->session()->get('dependencia_tramite_id');
        } else {
            $dependencia_tramite_id = 0;
        }

        return view('frontend.form-turno-paso1')->with(compact('dependencias', 'dependencia_id', 'dependencia_tramite_id'));
    }

    public function paso2(Request $request)
    {
        $input = $request->all();

        $aHorarios = array();

        //Se administran los datos de session
        if (isset($input['dependencia_id'])) {
            $request->session()->put('dependencia_id', $input['dependencia_id']);
        }

        if (isset($input['dependencia_tramite_id'])) {
            $request->session()->put('dependencia_tramite_id', $input['dependencia_tramite_id']);
        }

        $dependencia_tramite_id = $request->session()->get('dependencia_tramite_id');
        $turno_tramite = Turnos_Tramites::where('dependencia_tramite_id', $dependencia_tramite_id)
            ->where('activo', true)
            ->where('fecha_hasta', '>=', Carbon::today())
            ->orderByDesc('id')
            ->first();

        if (!$turno_tramite) {
            return redirect()->route('tramite.index')->with('error', 'No hay turnos disponibles para este trámite.');
        }

        if ($turno_tramite->fecha_desde->format('Y-m-d') > Carbon::today()->format('Y-m-d')) {
            $fecha_desde = $turno_tramite->fecha_desde->format('d/m/Y');
        } else {
            $fecha_desde = Carbon::today()->format('d/m/Y');
        }

        $request->session()->put('turno_tramite_id', $turno_tramite->id);

        $turno_fecha = $request->session()->get('turno_fecha', 0);
        $turno_hora = $request->session()->get('turno_hora', 0);
        
        $feriados = Feriado::whereDate('fecha', '>=', Carbon::today())->get()->map(function ($feriado) {
            return $feriado->fecha->format('d/m/Y');
        })->toArray();
        $feriados_text = Feriado::whereDate('fecha', '>=', Carbon::today())->get();

        $turno_dependencia = $turno_tramite;

        return view('frontend.form-turno-paso2')->with(compact('turno_dependencia', 'turno_fecha', 'turno_hora', 'aHorarios', 'fecha_desde', 'feriados','feriados_text'));
    }

    public function paso3(Request $request)
    {
        try { 
            $input = $request->all();

            $request->session()->put('turno_fecha', $input['turno_fecha']);
            $request->session()->put('turno_hora', $input['turno_hora']);

            $dependencia_id = $request->session()->get('dependencia_id');
            $turno_fecha = $request->session()->get('turno_fecha');
            $turno_hora = $request->session()->get('turno_hora');

            $dependencia = Dependencia::find($dependencia_id)->toArray();

            $usuario = Auth::user();

            return view('frontend.form-turno-paso3')->with(compact('dependencia', 'turno_fecha', 'turno_hora', 'usuario'));
        } catch (\Exception $e) {
            return redirect(route('tramite.index'));
        }
    }

    public function guardar(StoreTurno $request)
    {
        $input = $request->all();

        $values = $request->session()->all();

        list($turno_hora, $turno_horario_id) = explode('|', $request->session()->get('turno_hora'));

        // START of the check
        $turno_horario = \App\Models\Turnos_Horarios::find($turno_horario_id);
        $fecha = Carbon::createFromFormat('d/m/Y', $request->session()->get('turno_fecha'));

        $reservas_count = Turnos_Dependencias_Reservas::where('turno_horario_id', $turno_horario_id)
            ->whereDate('fecha', $fecha)
            ->where('hora', $turno_hora)
            ->count();

        if ($reservas_count >= $turno_horario->cantidad_turnos) {
            return redirect()->route('tramite.index')->with('error', 'El turno seleccionado ya no está disponible. Por favor, elija otro horario.');
        }
        // END of the check

        $aux_input = [
            'fecha_hora' => Carbon::createFromFormat('d/m/Y H:i', $request->session()->get('turno_fecha').' '.$turno_hora),
            'fecha' => Carbon::createFromFormat('d/m/Y', $request->session()->get('turno_fecha')),
            'hora' => $turno_hora,
            'nombre_apellido' => $input['nombre_apellido'],
            'dni' => $input['dni'],
            'celular' => $input['celular'],
            'email' => $input['email'],
            'turno_horario_id' => $turno_horario_id,
            'dependencia_tramite_id' => $request->session()->get('dependencia_tramite_id'),
            'estado_id' => 1,
            'activo' => 1
        ];

        $turno_reserva = Turnos_Dependencias_Reservas::create($aux_input);

        $dependencia_codigo = $turno_reserva->turno_horario->turno_tramite->tramite->dependencia->codigo;
        $turno_reserva->codigo = $dependencia_codigo.str_pad($turno_reserva->id, 6, "0", STR_PAD_LEFT);
        $turno_reserva->save();

        $request->session()->forget('dependencia_id');
        $request->session()->forget('dependencia_tramite_id');
        $request->session()->forget('turno_tramite_id');
        $request->session()->forget('turno_fecha');
        $request->session()->forget('turno_hora');
        
        return view('frontend.form-turno-paso4')->with(compact('turno_reserva'));
    }

    public function print($id)
    {
        $turno_reserva = Turnos_Dependencias_Reservas::with('turno_tramite.tramite.dependencia')->find($id);

        $turno_reserva->fecha_hora_text = $turno_reserva->fecha_hora->format('d/m/Y h:i');

        $html = view('htmltopdf.turno_comprobante')
            ->with('turno_reserva', $turno_reserva)->render();
       
        $pdf = \PDF::loadHTML($html);
        return $pdf->download('comprobante_'.$turno_reserva->codigo.'.pdf'); 
    }

    protected function buscar(SearchTurno $request)
    {
        $input = $request->all();
        $turno_reserva_busqueda = Turnos_Dependencias_Reservas::with('turno_tramite.tramite.dependencia')->where('codigo', $input['codigo_turno'])->first();

        return back()->with('turno_reserva_busqueda', $turno_reserva_busqueda);
    }

    public function loadHorarios(Request $request)
    {
        $input = $request->all();

        $turno_tramite = Turnos_Tramites::with('turnosHorarios')->find($input['id']);

        if (!$turno_tramite) {
            return 'Error: Configuración de turnos no encontrada.';
        }

        $turno_fecha = Carbon::createFromFormat('d/m/Y', $input['turno_fecha']);

        $reservas = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $turno_tramite->dependencia_tramite_id)
            ->whereDate('fecha', $turno_fecha)
            ->select('hora', DB::raw('count(*) as total'))
            ->groupBy('hora')
            ->get()
            ->pluck('total', 'hora')
            ->toArray();

        $aHorarios = [];
        $now = Carbon::now();

        foreach ($turno_tramite->turnosHorarios as $horario) {
            if (!$horario->activo) {
                continue;
            }

            $tStart = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_inicio);
            $tEnd = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_fin);
            $duration = $horario->duracion_minutos;

            $tCurrent = $tStart->copy();

            while ($tCurrent < $tEnd) {
                $isPastSlotForToday = $turno_fecha->isToday() && $tCurrent < $now;
                
                $slot = $tCurrent->format('H:i');
                $reservas_count = $reservas[$slot] ?? 0;

                if (!$isPastSlotForToday && $reservas_count < $horario->cantidad_turnos) {
                    $aHoras[] = [
                        'hora' => $slot,
                        'id' => $horario->id
                    ];
                }

                $tCurrent->addMinutes($duration);
            }
        }
        
        sort($aHoras);
        $aHoras = array_unique($aHoras, SORT_REGULAR);

        if (empty($aHoras)) {
            $html = 'Sin turnos. Por favor seleccione otro día.';
        } else {
            $html = '<select name="turno_hora" size="30" class="select list-group overflow-auto text-center" style="max-height: 267px; margin-bottom: 10px; width: 100%; border: 0px;" required="true">';

            foreach ($aHoras as $horario) {
                $html = $html.'<option class="list-group-item" value="'.$horario['hora'].'|'.$horario['id'].'">'.$horario['hora'].'</option>';
            }
            $html = $html.'</select>';
        }

        return $html;
    }

    public function getDirecciones()
    {
        //Se obtiene las dependencias que tienen emiten turnos
        $dependencias = Dependencia::getDependenciasConTurnos();

        $dependencias = (array) $dependencias;
        header('Content-Type: application/json');
        return json_encode($dependencias);
    }
    
    public function getTramites($id)
    {
        //Se obtiene las dependencias que tienen emiten turnos
        $dependencias_tramites = Dependencia_Tramite::where('dependencia_id', $id)->get()->toArray();

        header('Content-Type: application/json');
        return json_encode($dependencias_tramites);
    }

    public function getDisabledDates(Request $request)
    {
        Log::info('getDisabledDates called');
        $input = $request->all();
        Log::info('Input:', $input);
        $turno_tramite = Turnos_Tramites::with('turnosHorarios')->find($input['id']);

        if (!$turno_tramite) {
            Log::info('Turno tramite not found');
            return response()->json([]);
        }
        Log::info('Turno tramite found:', $turno_tramite->toArray());


        $fecha_desde = $turno_tramite->fecha_desde;
        if (Carbon::today() > $fecha_desde) {
            $fecha_desde = Carbon::today();
        }
        $fecha_hasta = $turno_tramite->fecha_hasta;
        Log::info('Fecha desde: '.$fecha_desde->format('Y-m-d'));
        Log::info('Fecha hasta: '.$fecha_hasta->format('Y-m-d'));


        $disabled_dates = [];
        $current_date = $fecha_desde->copy();

        $reservas = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $turno_tramite->dependencia_tramite_id)
            ->whereDate('fecha', '>=', $fecha_desde)
            ->whereDate('fecha', '<=', $fecha_hasta)
            ->select(DB::raw('DATE(fecha) as fecha_date'), 'hora', DB::raw('count(*) as total'))
            ->groupBy('fecha_date', 'hora')
            ->get()
            ->groupBy('fecha_date');
        Log::info('Reservas:', $reservas->toArray());


        while ($current_date <= $fecha_hasta) {
            Log::info('Checking date: '.$current_date->format('Y-m-d'));
            $aHoras = [];
            $turno_fecha = $current_date;
            $reservas_del_dia = $reservas->get($turno_fecha->format('Y-m-d'));

            $reservas_por_hora = [];
            if ($reservas_del_dia) {
                $reservas_por_hora = $reservas_del_dia->pluck('total', 'hora')->toArray();
            }
            Log::info('Reservas del dia:', $reservas_por_hora);


            foreach ($turno_tramite->turnosHorarios as $horario) {
                if (!$horario->activo) {
                    continue;
                }
                Log::info('Checking horario:', $horario->toArray());


                $tStart = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_inicio);
                $tEnd = $turno_fecha->copy()->setTimeFromTimeString($horario->hora_fin);
                $duration = $horario->duracion_minutos;

                $tCurrent = $tStart->copy();

                while ($tCurrent < $tEnd) {
                    $slot = $tCurrent->format('H:i');
                    $reservas_count = $reservas_por_hora[$slot] ?? 0;
                    Log::info('Slot: '.$slot.' - Reservas: '.$reservas_count.' - Capacidad: '.$horario->cantidad_turnos);


                    if ($reservas_count < $horario->cantidad_turnos) {
                        $aHoras[] = $slot;
                    }

                    $tCurrent->addMinutes($duration);
                }
            }

            if (empty($aHoras)) {
                Log::info('Date disabled: '.$current_date->format('d/m/Y'));
                $disabled_dates[] = $current_date->format('d/m/Y');
            }

            $current_date->addDay();
        }

        Log::info('Disabled dates:', $disabled_dates);
        return response()->json($disabled_dates);
    }
}
