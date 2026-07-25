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
            'tipo_evento_id' => 'nullable|integer|exists:tipos_evento,id',
            'proyecto_extension_id' => 'nullable|integer|exists:proyectos_extension,id',
            'responsable_id' => 'nullable|integer|exists:users,id',
            'horarios' => 'array|required',
            'horarios.*.hora_inicio' => 'required_if:horarios.*.activo,1',
            'horarios.*.hora_fin' => 'required_if:horarios.*.activo,1',
            'horarios.*.duracion_minutos' => 'required_if:horarios.*.activo,1|integer|min:5|max:480',
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
            $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
            
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
                            "En la franja #" . ($index + 1) . ", la hora de fin debe ser mayor que la hora de inicio."
                        );
                        continue;
                    }

                    $diferenciaMinutos = ($fin - $inicio) / 60;

                    // Validar que la duración no sea mayor que el tiempo disponible entre inicio y fin
                    if (isset($horario['duracion_minutos'])) {
                        $duracion = (int) $horario['duracion_minutos'];

                        if ($duracion > $diferenciaMinutos) {
                            $validator->errors()->add(
                                "horarios.{$index}.duracion_minutos",
                                "En la franja #" . ($index + 1) . ", la duración del turno ({$duracion} min) es mayor que el rango disponible entre inicio y fin ({$diferenciaMinutos} min)."
                            );
                        }

                        if ($duracion > 0 && ($duracion % 5 !== 0)) {
                            $validator->errors()->add(
                                "horarios.{$index}.duracion_minutos",
                                "En la franja #" . ($index + 1) . ", la duración debe ser en intervalos de 5 minutos."
                            );
                        }
                    }

                    // Validar que al menos un día esté seleccionado
                    $diasSeleccionados = false;
                    foreach ($dias as $dia) {
                        if (isset($horario[$dia]) && $horario[$dia] == 1) {
                            $diasSeleccionados = true;
                            break;
                        }
                    }

                    if (!$diasSeleccionados) {
                        $validator->errors()->add(
                            "horarios.{$index}.lunes",
                            "En la franja #" . ($index + 1) . ", debe seleccionar al menos un día de la semana habilitado."
                        );
                    }

                    // Validar solapamiento únicamente si las franjas comparten días de atención
                    foreach ($intervalos as $intervalo) {
                        $compartenDia = false;
                        foreach ($dias as $dia) {
                            if ((isset($horario[$dia]) && $horario[$dia] == 1) && (isset($intervalo['horario'][$dia]) && $intervalo['horario'][$dia] == 1)) {
                                $compartenDia = true;
                                break;
                            }
                        }

                        if ($compartenDia) {
                            if (
                                ($inicio >= $intervalo['inicio'] && $inicio < $intervalo['fin']) ||
                                ($fin > $intervalo['inicio'] && $fin <= $intervalo['fin']) ||
                                ($inicio <= $intervalo['inicio'] && $fin >= $intervalo['fin'])
                            ) {
                                $validator->errors()->add(
                                    "horarios.{$index}.hora_inicio",
                                    "La franja #" . ($index + 1) . " se solapa en el horario con la franja #" . ($intervalo['index'] + 1) . " para los mismos días de atención."
                                );
                                break;
                            }
                        }
                    }

                    $intervalos[] = [
                        'inicio' => $inicio,
                        'fin' => $fin,
                        'horario' => $horario,
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
            'horarios.*.duracion_minutos.max' => 'La duración máxima es de 480 minutos (8 horas)',
            'horarios.*.duracion_minutos.multiple_of' => 'La duración debe ser en intervalos de 5 minutos',
            'horarios.*.hora_inicio.required_if' => 'La hora de inicio es requerida cuando el horario está activo',
            'horarios.*.hora_fin.required_if' => 'La hora de fin es requerida cuando el horario está activo',
            'horarios.*.duracion_minutos.required_if' => 'La duración es requerida cuando el horario está activo',
        ];
    }
}
