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
            'activo' => 'nullable',
            'permite_grupal' => 'nullable',
            'tipo_modalidad' => 'nullable|string|in:individual,grupal,institucional,mixto',
            'max_personas_reserva' => 'nullable|integer|min:1',
            'min_personas_reserva' => 'nullable|integer|min:1',
            'requiere_institucion' => 'nullable',
            'requiere_nomina' => 'nullable',
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
