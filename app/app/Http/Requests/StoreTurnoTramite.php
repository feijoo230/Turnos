<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTurnoTramite extends FormRequest
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
            'dependencia_tramite_id' => 'required|integer',
            'fecha_desde' => 'required|date|after_or_equal:today',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
            'activo' => 'required|integer',
            'horarios' => 'array|required',
            'horarios.*.hora_inicio' => 'required_if:horarios.*.activo,1',
            'horarios.*.hora_fin' => 'required_if:horarios.*.activo,1',
            'horarios.*.duracion_minutos' => 'required_if:horarios.*.activo,1|integer|min:5|max:60',
            'horarios.*.activo' => 'boolean',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $horarios = $this->input('horarios');
            
            if (!is_array($horarios)) {
                return;
            }

          
            $intervalos = [];
            
            
            foreach ($horarios as $index => $horario) {
                if (!isset($horario['activo']) || $horario['activo'] != 1) {
                    continue;
                }

                if (isset($horario['hora_inicio']) && isset($horario['hora_fin'])) {
                    $inicio = strtotime($horario['hora_inicio']);
                    $fin = strtotime($horario['hora_fin']);
                    
                   
                    if ($fin <= $inicio) {
                        $validator->errors()->add(
                            "horarios.{$index}.hora_fin",
                            'La hora de fin debe ser mayor que la hora de inicio'
                        );
                        continue;
                    }

                    
                    if (isset($horario['duracion_minutos']) && ($horario['duracion_minutos'] % 5 !== 0)) {
                        $validator->errors()->add(
                            "horarios.{$index}.duracion_minutos",
                            'La duración debe ser en intervalos de 5 minutos'
                        );
                    }

                    
                    foreach ($intervalos as $idx => $intervalo) {
                        if (
                            
                            ($inicio >= $intervalo['inicio'] && $inicio < $intervalo['fin']) ||
                            
                            ($fin > $intervalo['inicio'] && $fin <= $intervalo['fin']) ||
                            
                            ($inicio <= $intervalo['inicio'] && $fin >= $intervalo['fin'])
                        ) {
                            $validator->errors()->add(
                                "horarios.{$index}.hora_inicio",
                                'Este horario se solapa con otro intervalo existente'
                            );
                            break;
                        }
                    }

                  
                    $intervalos[] = [
                        'inicio' => $inicio,
                        'fin' => $fin,
                        'index' => $index
                    ];
                }
            }
        });
    }
    
    public function attributes()
    {
        return [
            'dependencia_tramite_id' => 'TRAMITE',
            'fecha_desde' => 'FECHA DESDE',
            'fecha_hasta' => 'FECHA HASTA',
            'activo' => 'ACTIVO',
            'horarios.*.hora_inicio' => 'HORA INICIO',
            'horarios.*.hora_fin' => 'HORA FIN',
            'horarios.*.duracion_minutos' => 'DURACIÓN EN MINUTOS',
            'horarios.*.activo' => 'HORARIO ACTIVO',
        ];
    }

    public function messages()
    {
        return [
            'horarios.*.duracion_minutos.min' => 'La duración mínima es de 5 minutos',
            'horarios.*.duracion_minutos.max' => 'La duración máxima es de 60 minutos',
            'horarios.*.duracion_minutos.multiple_of' => 'La duración debe ser en intervalos de 5 minutos',
            'horarios.*.hora_inicio.required_if' => 'La hora de inicio es requerida cuando el horario está activo',
            'horarios.*.hora_fin.required_if' => 'La hora de fin es requerida cuando el horario está activo',
            'horarios.*.duracion_minutos.required_if' => 'La duración es requerida cuando el horario está activo',
        ];
    }
}
