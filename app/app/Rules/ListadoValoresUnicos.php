<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ListadoValoresUnicos implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $total = null;

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
        $aAux = array();
        foreach ($value as $value) {
            if (isset($value)) {
                $aAux[] = $value;
            }
        }
        $value = $aAux;
        $longitudDeUnicos = count(array_unique($value));
        $longitudOriginal = count($value);
          
        return ($longitudOriginal == $longitudDeUnicos);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La lista de codigos ingresados tiene repetidos.';
    }
}
