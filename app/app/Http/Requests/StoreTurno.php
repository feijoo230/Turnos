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

    protected function prepareForValidation()
    {
        if (!$this->has('cantidad_personas') || empty($this->input('cantidad_personas'))) {
            $this->merge([
                'cantidad_personas' => 1,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $dependencia_tramite_id = $this->input('dependencia_tramite_id') ?? session('dependencia_tramite_id');
        $dependencia_tramite = $dependencia_tramite_id ? \App\Models\Dependencia_Tramite::find($dependencia_tramite_id) : null;

        $requiereNomina = $dependencia_tramite ? (bool) ($dependencia_tramite->requiere_nomina ?? false) : false;
        $isInstitucionalSubmission = $this->filled('nombre_institucion') || 
            ($dependencia_tramite && ($dependencia_tramite->tipo_modalidad === 'institucional' || (bool) ($dependencia_tramite->requiere_institucion ?? false)));
        $minPersonas = ($dependencia_tramite && $dependencia_tramite->min_personas_reserva) ? (int) $dependencia_tramite->min_personas_reserva : 2;

        $rules = [
            'fecha_hora' => 'date',
            'fecha' => 'date_format:d/m/Y',
            'hora' => 'date_format:H:i',
            'nombre_apellido' => 'required|max:255',
            'dni' => 'required|max:255',
            'celular' => 'required|string',
            'email' => 'required|email|confirmed',
            'es_grupal' => 'nullable|boolean',
            'cantidad_personas' => 'nullable|integer|min:1',
            'nombre_institucion' => 'nullable|string|max:255',
            'cargo_responsable' => 'nullable|string|max:255',
            'nivel_institucion' => 'nullable|string|max:255',
            'cantidad_acompanantes' => 'nullable|integer|min:0',
            'curso_comision' => 'nullable|string|max:255',
            'archivo_integrantes' => 'nullable|file|mimes:xls,xlsx,csv|max:2048',
            'dependencia_turno_id' => 'integer',
            'estado_id' => 'integer',
            'activo' => 'integer',
        ];

        if ($this->boolean('es_grupal')) {
            $rules['cantidad_personas'] = "required|integer|min:{$minPersonas}";

            if ($isInstitucionalSubmission) {
                $rules['nombre_institucion'] = 'required|string|max:255';
                $rules['nivel_institucion'] = 'required|string|max:255';
                $rules['cargo_responsable'] = 'required|string|max:255';
            }

            if ($requiereNomina) {
                $rules['archivo_integrantes'] = 'required|file|mimes:xls,xlsx,csv|max:2048';
            }
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
            'archivo_integrantes' => 'NÓMINA DE INTEGRANTES',
            'nombre_institucion' => 'NOMBRE DE LA INSTITUCIÓN',
            'nivel_institucion' => 'NIVEL EDUCATIVO',
            'cargo_responsable' => 'CARGO DEL RESPONSABLE',
        ];
    }
}
