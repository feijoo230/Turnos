<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Resolucion_Estado;

class CargoStock implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $tipo_cargo_id = null; 
    private $dependencia_id = null;
    private $cantidad = null;
    private $total = null;

    public function __construct($tipo_cargo_id, $dependencia_id, $cantidad)
    {
        $this->tipo_cargo_id = $tipo_cargo_id; 
        $this->dependencia_id = $dependencia_id;
        $this->cantidad = $cantidad;     
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //FILTROS - CARGOS OCUPADOS
        $whereData = array();

        $whereData = $whereData + ['tipos_cargos.id' => $this->tipo_cargo_id];
        $whereData = $whereData + ['dependencias.id' => $this->dependencia_id];

        //FILTROS - CARGOS OCUPADOS

        //CARGOS DISPONIBLES
        $cargos_dispisponibles = DB::table('resoluciones_cargos')
        ->select(DB::raw('estamentos.name, estamentos.acronimo, tipos_cargos.id as tipo_cargo_id, tipos_cargos.estamento_id, resoluciones_abm.dependencia_id, SUM(resoluciones_cargos.cantidad) as total', 'dependencias.parent_id'))
        ->join('resoluciones_abm', 'resoluciones_abm.id', '=', 'resoluciones_cargos.resolucion_abm_id')
        ->join('resoluciones', 'resoluciones.id', '=', 'resoluciones_abm.resolucion_id')
        ->join('tipos_cargos', 'tipos_cargos.id', '=', 'resoluciones_cargos.tipo_cargo_id')
        ->join('estamentos', 'estamentos.id', '=', 'tipos_cargos.estamento_id')
        ->join('dependencias', 'dependencias.id', '=', 'resoluciones_abm.dependencia_id')
        ->where($whereData)
        ->groupBy('estamentos.name', 'estamentos.acronimo', 'tipos_cargos.id', 'tipos_cargos.estamento_id', 'resoluciones_abm.dependencia_id')
        ->orderBy('resoluciones_abm.dependencia_id', 'tipos_cargos.estamento_id')
        ->get()
        ->toArray();

         //CARGOS OCUPADOS
        $cargos_ocupados = DB::table('cargos_pau')
        ->select(DB::raw('tipos_cargos.id as tc_id, tipos_cargos.estamento_id, cargos_pau.dependencia_id, SUM(cargos_pau.cantidad_stock) as total', 'dependencias.parent_id'))
        ->leftjoin('resoluciones', 'resoluciones.id', '=', 'cargos_pau.resolucion_id')
        ->join('tipos_cargos', 'tipos_cargos.id', '=', 'cargos_pau.tipo_cargo_id')
        ->join('estamentos', 'estamentos.id', '=', 'tipos_cargos.estamento_id')
        ->join('dependencias', 'dependencias.id', '=', 'cargos_pau.dependencia_id')
        ->where('resoluciones.resolucion_estado_id', Resolucion_Estado::ACTIVO)
        ->where($whereData)
        ->groupBy('tipos_cargos.id', 'tipos_cargos.estamento_id', 'cargos_pau.dependencia_id')
        ->orderBy('cargos_pau.dependencia_id', 'tipos_cargos.estamento_id')
        ->get()
        ->toArray();

        $cargos_ocupados_val = (isset($cargos_ocupados[0]->total)? $cargos_ocupados[0]->total : 0);
        $total = $cargos_dispisponibles[0]->total - $cargos_ocupados_val;
        $this->total = $total;
        
        return $total >= $this->cantidad;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La campo Cantidad supera los Cargos Disponibles ('.$this->total.')';
    }
}
