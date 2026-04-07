<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchTurno extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'codigo_turno' => 'required_without:dni_turno|max:255',
            'dni_turno' => 'required_without:codigo_turno|max:20'
        ];
    }

    public function attributes()
    {
        return [
            'codigo_turno' => 'CODIGO DE TURNO',
            'dni_turno' => 'DNI'
        ];
    }
}
