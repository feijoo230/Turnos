<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDependencia extends FormRequest
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
            'parent_id' => 'required',
            'nombre' => 'required|max:255',
            'tipo_dependencia_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'parent_id' => 'DEPENDENCIA PADRE',
            'nombre' => 'NOMBRE',
            'tipo_dependencia_id' => 'TIPO DE DEPENDENCIA',
            'es_unidad_academica' => 'ES UNIDAD ACADEMICA'
        ];
    }
}
