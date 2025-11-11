<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Cargos_Unicos_Estados;
use Illuminate\Support\Facades\DB;

class CargoUnicoCodigo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */    

    public function __construct()
    {
        //
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
        if (preg_match("/^[0-9]+$/", $value)){
            //FILTROS - CARGOS UNICO
            $whereData = array();

            $whereData = $whereData + ['cargos_unicos.id' => $value];

            //CARGOS DISPONIBLES
            $cargo_disponible = DB::table('cargos_unicos')
            ->where($whereData)
            ->where('cargo_unico_estado_id', Cargos_Unicos_Estados::VACANTE)
            ->count();

            return $cargo_disponible == 1;        
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El cargo :input ya se encuentra utilizado.';
    }
}
