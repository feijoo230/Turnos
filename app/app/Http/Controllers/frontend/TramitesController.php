<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Dependencia;
use App\Models\Turnos_Dependencias;
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

        //var_dump($dependencia_id);
        
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

        $turno_dependencia = Turnos_Dependencias::where('dependencia_id', $request->session()->get('dependencia_id'))
            ->orderByDesc('id')
            ->first();

        if ($turno_dependencia->fecha_desde->format('Y-m-d') > Carbon::today()) {
            $fecha_desde = $turno_dependencia->fecha_desde->format('d/m/Y');
        } else {
            $fecha_desde = Carbon::today()->format('d/m/Y');
        }


        $request->session()->put('dependencia_turno_id', $turno_dependencia->id);

        $aux_fecha = $request->session()->get('turno_fecha');
        if (isset($aux_fecha)) {
            $turno_fecha = $request->session()->get('turno_fecha');
        } else {
            $turno_fecha = 0;
        }
        
        $aux_hora = $request->session()->get('turno_hora');
        if (isset($aux_hora)) {
            $turno_hora = $request->session()->get('turno_hora');
        } else {
            $turno_hora = 0;
        }
        //**************
        //Feriados
        $feriados = Feriado::whereDate('fecha', '>=', Carbon::today())->get('fecha')->pluck('fecha')->toArray();

        $feriados_text = Feriado::whereDate('fecha', '>=', Carbon::today())->get();

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

        $turno_dependencia = Turnos_Dependencias::find($request->session()->get('dependencia_turno_id'));

        $aux_input = [
            'fecha_hora' => Carbon::createFromFormat('d/m/Y H:i', $request->session()->get('turno_fecha').' '.$request->session()->get('turno_hora')),
            'fecha' => Carbon::createFromFormat('d/m/Y', $request->session()->get('turno_fecha')),
            'hora' => $request->session()->get('turno_hora'),
            'nombre_apellido' => $input['nombre_apellido'],
            'dni' => $input['dni'],
            'celular' => $input['celular'],
            'email' => $input['email'],
            'dependencia_turno_id' => $request->session()->get('dependencia_turno_id'),
            'dependencia_tramite_id' => $request->session()->get('dependencia_tramite_id'),
            'estado_id' => 1,
            'activo' => 1
        ];

        $turno_reserva = Turnos_Dependencias_Reservas::create($aux_input);

        $turno_reserva->codigo = $turno_dependencia->dependencia->codigo.str_pad($turno_reserva->id, 6, "0", STR_PAD_LEFT);
        $turno_reserva->save();

        $request->session()->flush();
        
        //return redirect(route('turnos'));
        return view('frontend.form-turno-paso4')->with(compact('turno_reserva'));
    }

    public function print($id)
    {
        $turno_reserva = Turnos_Dependencias_Reservas::find($id);

        $turno_reserva->fecha_hora_text = $turno_reserva->fecha_hora->format('d/m/Y h:i');

        $html = view('htmltopdf.turno_comprobante')
            ->with('turno_reserva', $turno_reserva)->render();
       
        $pdf = \PDF::loadHTML($html);
        return $pdf->download('comprobante_'.$turno_reserva->codigo.'.pdf'); 
    }

    protected function buscar(SearchTurno $request)
    {
        $input = $request->all();
        $turno_reserva_busqueda = Turnos_Dependencias_Reservas::where('codigo', $input['codigo_turno'])->first();

        //Se obtiene las dependencias que tienen emiten turnos
        $dependencias = Dependencia::getDependenciasConTurnos();

        $aux = $request->session()->get('dependencia_id');
        if (isset($aux)) {
            $dependencia_id = $request->session()->get('dependencia_id');
        } else {
            $dependencia_id = 0;
        }

        return view('frontend.form-turno-paso1')->with(compact('dependencias', 'turno_reserva_busqueda', 'dependencia_id'));
    }

    public function loadHorarios(Request $request)
    {
        $input = $request->all();
        
        $turno_dependencia = Turnos_Dependencias::find($input['id']);

        $turno_fecha = Carbon::createFromFormat('d/m/Y',$input['turno_fecha']);

        $turno_dependencia_reservas = Turnos_Dependencias_Reservas::where('dependencia_turno_id', $turno_dependencia->id)->where('fecha', $turno_fecha->format('Y-m-d'))->get();

        $tStart = Carbon::createFromFormat('H:i:s',$turno_dependencia->hora_desde);

        
        $tStart_condicion = $tStart;


        
        

     
            if (Carbon::createFromFormat('H:i:s',$turno_dependencia->hora_desde) > Carbon::now()) {
                $tStart_condicion = Carbon::createFromFormat('H:i:s',$turno_dependencia->hora_desde);
            } else {
                $tStart_condicion = Carbon::now();
            }
       


        $tEnd = Carbon::createFromFormat('H:i:s',$turno_dependencia->hora_hasta);
        $tNow = $tStart;

        $aHoras = array();

        $aux = strtotime($tNow);
        $tEnd = strtotime($tEnd);

        $fecha_selec = $input['turno_fecha'];

        while($aux <= $tEnd) {
            if (($tNow > $tStart_condicion) or (strcmp($turno_fecha->format('d/m/Y'), Carbon::today()->format('d/m/Y')) != 0)) {
                if (!$turno_dependencia_reservas->isEmpty()) {
                    $band_agregar = TRUE;
                    foreach ($turno_dependencia_reservas as $reserva) {
                        $aux = $reserva->hora;

                        if (strcmp($aux, $tNow->format('H:i:s')) == 0) {
                            $band_agregar = FALSE;
                        }
                    }
                    if ($band_agregar) {
                        $aHoras[] = $tNow->format('H:i');
                    }
                } else {
                    $aHoras[] = $tNow->format('H:i');
                }
            }

            $tNow = $tNow->addMinutes($turno_dependencia->intervalo);
            $aux = strtotime($tNow);
        }

        if (empty($aHoras)) {
            $html = 'Sin turnos. Por favor seleccione otro día.';
        } else {
            //Se arma el string html
            $html = '<select name="turno_hora" size="30" class="select list-group overflow-auto text-center" style="max-height: 267px; margin-bottom: 10px; width: 100%; border: 0px;" required="true">';

            foreach ($aHoras as $horario) {
                $html = $html.'<option class="list-group-item" value="'.$horario.'">'.$horario.'</option>';
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
}
