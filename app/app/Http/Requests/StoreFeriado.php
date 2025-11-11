<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeriado extends FormRequest
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
            'fecha' => 'required|date_format:d/m/Y',
            'observacion' => 'max:255',
            'activo' => 'integer'
        ];
    }

    public function attributes()
    {
        return [
            'fecha' => 'FECHA',
            'observacion' => 'OBSERVACION',
            'activo' => 'ACTIVO'
        ];
    }
}
