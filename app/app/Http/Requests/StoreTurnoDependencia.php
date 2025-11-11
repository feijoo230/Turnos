<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTurnoDependencia extends FormRequest
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
            'intervalo' => 'required|integer',
            'hora_desde' => 'required|date_format:H:i',
            'hora_hasta' => 'required|date_format:H:i',
            'fecha_desde' => 'required|after_or_equal:now|date_format:d/m/Y',
            'fecha_hasta' => 'required|date_format:d/m/Y|after_or_equal:fecha_desde',
            'lunes' => 'required|boolean',
            'martes' => 'required|boolean',
            'miercoles' => 'required|boolean',
            'jueves' => 'required|boolean',
            'viernes' => 'required|boolean',
            'dependencia_id' => 'required|integer',
            'activo' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'intervalo' => 'INTERVALO',
            'hora_desde' => 'HORA DESDE',
            'hora_hasta' => 'HORA HASTA',
            'fecha_desde' => 'FECHA DESDE',
            'fecha_hasta' => 'FECHA HASTA',
            'lunes' => 'LUNES',
            'martes' => 'MARTES',
            'miercoles' => 'MIERCOLES',
            'jueves' => 'JUEVES',
            'viernes' => 'VIERNES',
            'dependencia_id' => 'DEPENDENCIA',
            'activo' => 'ACTIVO',
            
        ];
    }
}
