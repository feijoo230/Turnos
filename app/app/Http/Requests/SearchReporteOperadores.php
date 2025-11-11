<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchReporteOperadores extends FormRequest
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
            'fecha_desde' => 'required',
            'fecha_hasta' => 'required|after_or_equal:fecha_desde',
            'es_afiliado' => ''
        ];
    }

    public function attributes()
    {
        return [
            'fecha_desde' => 'FECHA DESDE',
            'fecha_hasta' => 'FECHA HASTA',
            'es_afiliado' => '¿ES AFILIADO?'
        ];
    }
}