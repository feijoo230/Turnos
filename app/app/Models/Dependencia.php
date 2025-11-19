<?php

namespace App\Models;


use Eloquent as Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Class Rol
 * @package App\Models
 * @version October 21, 2016, 8:46 pm UTC
 */
class Dependencia extends Model
{
    use NodeTrait;

    public $table = 'dependencias';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const ROOT_ID = 1;
    const ACTIVO = 1;

    protected $dates = ['deleted_at'];

    protected $stringPath;
    
    public $fillable = [
        'nombre',
        'codigo',
        '_lft',
        '_rgt',
        'parent_id',
        'unidad_academica_id',
        'es_unidad_academica',
        'tipo_dependencia_id',
        'nivel'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre' => 'string',
        '_lft' => 'integer',
        '_rgt' => 'integer',
        'parent_id' => 'integer',
        'unidad_academica_id' => 'integer',
        'es_unidad_academica' => 'boolean',
        'tipo_dependencia_id' => 'integer',
        'nivel' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_dependencias', 'dependencia_id', 'usuario_id');
    }

    public function usuario_origen()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_dependencia_origen', 'dependencia_id', 'usuario_id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

    public function getNameAttribute($value)
    {
        return $this->attributes['nombre'];        
    }

    public function stringPath()
    {
       if ($this->stringPath) return $this->name;

       $parent = $this->parent;
       return $this->stringPath = $parent ? $parent->stringPath().' > '.$this->name : $this->name;
    }

    public function arrayTree()
    {
       if ($this->arrayTree) return [$this->id => $this->name];

       $parent = $this->parent;
       return $this->arrayTree[] = $parent ? $parent->stringPath().' > '.$this->name : [$this->id => $this->name];
    }

    public static function selectTree($tree = null, $prefix = '—>', &$result = null)
    {
        foreach ($tree as $subTree) {
            $result = $result + [$subTree->id => $prefix.' '.$subTree->name];

            Dependencia::selectTree($subTree->children, '— '.$prefix, $result);
        }
    }

    public static function selectTreeWithNivel($tree = null, $prefix = '—>', &$result = null)
    {
        foreach ($tree as $subTree) {

            $prefijo = '';
            $padre = Dependencia::find($subTree->parent_id);

            if (!is_null($padre)) {
            
	            for ($i=0; $i < $subTree->nivel; $i++) {
	                if ($i < $padre->nivel) {
	                    $prefijo = $prefijo . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	                } else {
	                    $prefijo = $prefijo . '— ';
	                }
	            }
	        }

            $result = $result + [$subTree->id => $prefijo.$prefix.' '.$subTree->name. ' ('.$subTree->nivel.')'];

            Dependencia::selectTreeWithNivel($subTree->children, ' '.$prefix, $result);
        }
    }

    public static function tableTree($tree = null, $prefix = '&#8212;>', &$result = null)
    {
        foreach ($tree as $subTree) {
            array_push($result, ['id' => $subTree->id, 'nombre' => $prefix.' '.$subTree->name, 'es_unidad_academica' => $subTree->es_unidad_academica]);

            Dependencia::tableTree($subTree->children, '&#8212; '.$prefix, $result);
        }
    }

    public static function tableTreeWithNivel($tree = null, $prefix = '&#8212;>', &$result = null)
    {
        foreach ($tree as $subTree) {

            $prefijo = '';
            $padre = Dependencia::find($subTree->parent_id);
            
            if (!is_null($padre)) {
            	
	            for ($i=0; $i < $subTree->nivel; $i++) {
	                if ($i < $padre->nivel) {
	                    $prefijo = $prefijo . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	                } else {
	                    $prefijo = $prefijo . '—&#8212; ';
	                }
	            }
	        }

            array_push($result, ['id' => $subTree->id, 'nombre' => $prefijo.$prefix.' '.$subTree->name, 'es_unidad_academica' => $subTree->es_unidad_academica]);

            Dependencia::tableTreeWithNivel($subTree->children, $prefix, $result);
        }
    }

    public static function getDependenciasConTurnos()
    {
        $dependencias = DB::table('mesas_habilitadas')
            ->join('dependencias', 'mesas_habilitadas.dependencia_id', '=', 'dependencias.id')
            ->where('mesas_habilitadas.activo', TRUE)
            ->orderBy('dependencias.nombre')
            ->get()
            ->keyBy('id')
            ->toArray();

        $today = Carbon::today();
        $feriados = Feriado::where('activo', 1)
            ->where('fecha', '>=', $today)
            ->get()
            ->map(function ($feriado) {
                return Carbon::createFromFormat('d/m/Y', $feriado->fecha)->format('Y-m-d');
            })->toArray();

        foreach ($dependencias as $id => &$dependencia) {
            $dependencia = (array) $dependencia;
            $dependencia['has_turns'] = false;
            
            $tramites = Dependencia_Tramite::where('dependencia_id', $id)->get();

            foreach ($tramites as $tramite) {
                $turno_tramite = Turnos_Tramites::where('dependencia_tramite_id', $tramite->id)
                    ->where('activo', true)
                    ->where('fecha_hasta', '>=', $today)
                    ->orderByDesc('id')
                    ->first();

                if ($turno_tramite) {
                    $fecha_desde = $turno_tramite->fecha_desde->isAfter($today) ? $turno_tramite->fecha_desde : $today;
                    $fecha_hasta = $turno_tramite->fecha_hasta;

                    $reservas_all = Turnos_Dependencias_Reservas::where('dependencia_tramite_id', $tramite->id)
                        ->whereDate('fecha', '>=', $fecha_desde)
                        ->whereDate('fecha', '<=', $fecha_hasta)
                        ->select(DB::raw('DATE(fecha) as fecha_date'), 'turno_horario_id', 'hora', DB::raw('count(*) as total'))
                        ->groupBy('fecha_date', 'turno_horario_id', 'hora')
                        ->get()
                        ->groupBy('fecha_date');
                    
                    for ($current_date = $fecha_desde->copy(); $current_date->lte($fecha_hasta); $current_date->addDay()) {
                        if ($current_date->isWeekend() || in_array($current_date->format('Y-m-d'), $feriados)) {
                            continue;
                        }

                        $reservas_del_dia = $reservas_all->get($current_date->format('Y-m-d'));
                        $isToday = $current_date->isToday();
                        
                        foreach ($turno_tramite->turnosHorarios as $horario) {
                            if (!$horario->activo) continue;

                            for ($tCurrent = $current_date->copy()->setTimeFromTimeString($horario->hora_inicio); $tCurrent->lt($current_date->copy()->setTimeFromTimeString($horario->hora_fin)); $tCurrent->addMinutes($horario->duracion_minutos)) {
                                $slot = $tCurrent->format('H:i:s');
                                $reservas_count = 0;
                                if($reservas_del_dia) {
                                    $reserva_slot = $reservas_del_dia->where('turno_horario_id', $horario->id)->where('hora', $slot)->first();
                                    if($reserva_slot) $reservas_count = $reserva_slot->total;
                                }

                                if (!$isToday || $tCurrent->isFuture()) {
                                    if ($reservas_count < $horario->cantidad_turnos) {
                                        $dependencia['has_turns'] = true;
                                        break 4;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return array_values($dependencias);
    }
}