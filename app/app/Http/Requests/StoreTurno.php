<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTurno extends FormRequest
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
        $rules = [
            'fecha_hora' => 'date',
            'fecha' => 'date_format:d/m/Y',
            'hora' => 'date_format:H:i',
            'nombre_apellido' => 'required|max:255',
            'dni' => 'required|max:255',
            'celular' => 'required|string',
            'email' => 'required|email|confirmed',
            'es_grupal' => 'nullable|boolean',
            'cantidad_personas' => 'required|integer|min:1',
            'nombre_institucion' => 'nullable|string|max:255',
            'archivo_integrantes' => 'nullable|file|mimes:xls,xlsx,csv|max:2048',
            'dependencia_turno_id' => 'integer',
            'estado_id' => 'integer',
            'activo' => 'integer',
        ];

        if ($this->input('es_grupal')) {
            $rules['cantidad_personas'] = 'required|integer|min:2';
            $rules['nombre_institucion'] = 'required|string|max:255';
            $rules['archivo_integrantes'] = 'required|file|mimes:xls,xlsx,csv|max:2048';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'fecha_hora' => 'FECHA HORA',
            'fecha' => 'FECHA',
            'hora' => 'HORA',
            'nombre_apellido' => 'NOMBRE Y APELLIDO',
            'dni' => 'DNI',
            'celular' => 'CELULAR',
            'email' => 'EMAIL',
            'dependencia_turno_id' => 'DEPENDENCIA',
            'estado_id' => 'ESTADO TURNO',
            'activo' => 'ACTIVO',
        ];
    }
}
