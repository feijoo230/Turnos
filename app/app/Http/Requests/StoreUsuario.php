<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuario extends FormRequest
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
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'. $this->get('id') .',id|max:255',
            'password' => 'required|max:255',
            'role_id' => 'required',
            'activo' => 'boolean'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'NOMBRE',
            'email' => 'EMAIL',
            'password' => 'PASSWORD',
            'role_id' => 'ROL',
            'activo' => 'ACTIVO'
        ];
    }
}
