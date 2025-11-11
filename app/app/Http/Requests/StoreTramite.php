<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTramite extends FormRequest
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
            'asunto' => 'required|max:255',
            'remitente' => 'required|max:255',
            'domicilio' => 'required|max:255',
            'telefono' => 'required|integer',
            'correo' => 'required|email',
            'dependencia_id' => 'required|integer',
            'files' => 'required',
            'activo' => 'integer'
        ];
    }

    public function attributes()
    {
        return [
            'asunto' => 'ASUNTO',
            'remitente' => 'CAUSANTE (REMITENTE)',
            'domicilio' => 'DOMICILIO',
            'telefono' => 'TELÉFONO',
            'correo' => 'EMAIL',
            'dependencia_id' => 'DESTINATARIO',
            'files' => 'DOCUMENTO/S'
        ];
    }
}
