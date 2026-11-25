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
            'codigo_turno' => 'required|string|max:50',
            'dni_turno' => 'required|string|max:20'
        ];
    }

    public function attributes()
    {
        return [
            'codigo_turno' => 'código de reserva',
            'dni_turno' => 'DNI'
        ];
    }

    public function messages()
    {
        return [
            'codigo_turno.required' => 'Por seguridad, ingresá el código de reserva junto con tu DNI.',
            'dni_turno.required' => 'Por seguridad, ingresá tu número de DNI junto con el código de reserva.',
        ];
    }
}
