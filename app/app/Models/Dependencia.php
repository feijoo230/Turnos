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
            ->keyBy('dependencia_id')
            ->toArray();

        foreach ($dependencias as $id => &$dependencia) {
            $dependencias[$id] = (array) $dependencia;
            $dependencias[$id]['count'] = 0;
        }

        $counts = DB::table('dependencias')
            ->join('dependencia_tramites', 'dependencias.id', '=', 'dependencia_tramites.dependencia_id')
            ->join('turnos_tramites', 'dependencia_tramites.id', '=', 'turnos_tramites.dependencia_tramite_id')
            ->where('turnos_tramites.activo', true)
            ->where('turnos_tramites.fecha_hasta', '>=', Carbon::now())
            ->groupBy('dependencias.id')
            ->select(DB::raw('count(*) as tramite_count, dependencias.id as dependencia_id'))
            ->get()
            ->keyBy('dependencia_id');

        foreach ($counts as $dependencia_id => $count) {
            if (isset($dependencias[$dependencia_id])) {
                $dependencias[$dependencia_id]['count'] = $count->tramite_count;
            }
        }
        
        return $dependencias;
    }
}