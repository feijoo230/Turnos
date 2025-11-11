<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTramitesDependecia extends FormRequest
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
            'nombre' => 'required|max:255',
            'dependencia_id' => 'required|integer|exists:dependencias,id',
            'activo' => 'integer'
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'NOMBRE',
            'dependencia_id' => 'DEPENDENCIA'
        ];
    }
}
