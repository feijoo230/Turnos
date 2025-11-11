<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusquedaTurno extends FormRequest
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
            'codigo_turno' => 'nullable',
            'fecha_turno' => 'nullable|date_format:d/m/Y',
        ];
    }

    public function attributes()
    {
        return [
            'codigo_turno' => 'CODIGO TURNO',
            'fecha_turno' => 'FECHA TURNO'
        ];
    }
}