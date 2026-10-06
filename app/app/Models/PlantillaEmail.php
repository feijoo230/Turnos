<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PlantillaEmail extends Model
{
    public $table = 'plantillas_email';

    protected $fillable = [
        'clave',
        'nombre',
        'circuito',
        'evento',
        'asunto',
        'cuerpo_html',
        'descripcion',
        'variables_disponibles',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /**
     * Scope por circuito
     */
    public function scopeCircuito($query, $circuito)
    {
        if (!empty($circuito) && $circuito !== 'todos') {
            return $query->where('circuito', $circuito);
        }
        return $query;
    }

    /**
     * Scope por evento
     */
    public function scopeEvento($query, $evento)
    {
        if (!empty($evento)) {
            return $query->where('evento', $evento);
        }
        return $query;
    }

    /**
     * Obtener lista de variables disponibles según el circuito
     */
    public static function getAvailableVariables($circuito = null)
    {
        $comunes = [
            '{codigo}' => 'Código oficial del turno (ej: TUR001234)',
            '{nombre}' => 'Nombre y apellido del solicitante o responsable',
            '{nombre_apellido}' => 'Nombre y apellido completo',
            '{dni}' => 'Número de documento / DNI',
            '{email}' => 'Correo electrónico del solicitante',
            '{telefono}' => 'Número telefónico / celular de contacto',
            '{celular}' => 'Celular de contacto',
            '{fecha}' => 'Fecha programada de la atención (ej: 15/10/2026)',
            '{hora}' => 'Hora asignada (ej: 10:30 hs)',
            '{tramite}' => 'Nombre del trámite o servicio solicitado',
            '{dependencia}' => 'Nombre de la dependencia u organismo',
            '{motivo_cancelacion}' => 'Motivo registrado de cancelación (si aplica)',
            '{anio_actual}' => 'Año actual en curso (ej: 2026)',
            '{sistema_nombre}' => 'Nombre institucional del sistema de turnos'
        ];

        $institucionales = [
            '{institucion}' => 'Nombre del colegio, escuela o entidad (ej: Colegio Nacional N° 5000)',
            '{nombre_institucion}' => 'Nombre completo de la institución',
            '{cargo}' => 'Cargo o función del responsable (ej: Vicedirector / Docente)',
            '{cargo_responsable}' => 'Cargo oficial del solicitante',
            '{nivel}' => 'Nivel educativo (ej: Primario, Secundario, Terciario)',
            '{nivel_institucion}' => 'Nivel de la institución educativa',
            '{curso}' => 'Curso, comisión o división (ej: 4to Año "B")',
            '{curso_comision}' => 'Identificación del curso o división',
            '{cantidad_personas}' => 'Cantidad total de alumnos o integrantes',
            '{cantidad_acompanantes}' => 'Cantidad de docentes acompañantes'
        ];

        if ($circuito === 'individual') {
            return $comunes;
        } elseif ($circuito === 'colegio' || $circuito === 'institucional') {
            return array_merge($comunes, $institucionales);
        }

        return array_merge($comunes, $institucionales);
    }

    /**
     * Datos simulados para previsualización y envíos de prueba
     */
    public static function getDummyData($circuito = 'individual')
    {
        if ($circuito === 'colegio' || $circuito === 'institucional') {
            return [
                'codigo' => 'COL000189',
                'nombre' => 'Prof. Mariana Valenzuela',
                'nombre_apellido' => 'Prof. Mariana Valenzuela',
                'dni' => '28.450.912',
                'email' => 'direccion@colegiobelgrano.edu.ar',
                'telefono' => '+54 387 512-3456',
                'celular' => '+54 387 512-3456',
                'fecha' => '22/10/2026',
                'hora' => '09:00 hs',
                'tramite' => 'Visita Guiada y Taller de Astronomía',
                'dependencia' => 'Observatorio Astronómico UNSa',
                'institucion' => 'Colegio Secundario Manuel Belgrano N° 5080',
                'nombre_institucion' => 'Colegio Secundario Manuel Belgrano N° 5080',
                'cargo' => 'Vicedirectora Turno Mañana',
                'cargo_responsable' => 'Vicedirectora Turno Mañana',
                'nivel' => 'Nivel Secundario',
                'nivel_institucion' => 'Nivel Secundario',
                'curso' => '4to Año - Divisiones A y B',
                'curso_comision' => '4to Año - Divisiones A y B',
                'cantidad_personas' => '45 estudiantes',
                'cantidad_acompanantes' => '4 docentes',
                'motivo_cancelacion' => 'Jornada pedagógica docente programada por el Ministerio de Educación.',
                'anio_actual' => date('Y'),
                'sistema_nombre' => config('constants.NOMBRE_SISTEMA', 'Sistema de Gestión de Turnos - UNSa')
            ];
        }

        return [
            'codigo' => 'TUR000452',
            'nombre' => 'Lic. Martín Gómez',
            'nombre_apellido' => 'Lic. Martín Gómez',
            'dni' => '34.892.110',
            'email' => 'mgomez@ejemplo.com',
            'telefono' => '+54 387 455-8921',
            'celular' => '+54 387 455-8921',
            'fecha' => '15/10/2026',
            'hora' => '10:30 hs',
            'tramite' => 'Acreditación y Certificación de Servicios',
            'dependencia' => 'Facultad de Ciencias Exactas - Dpto. Alumnos',
            'institucion' => 'Particular',
            'nombre_institucion' => 'Particular',
            'cargo' => 'Solicitante',
            'cargo_responsable' => 'Solicitante',
            'nivel' => 'Particular',
            'nivel_institucion' => 'Particular',
            'curso' => '-',
            'curso_comision' => '-',
            'cantidad_personas' => '1 persona',
            'cantidad_acompanantes' => '0',
            'motivo_cancelacion' => 'Imposibilidad de asistencia por motivos laborales.',
            'anio_actual' => date('Y'),
            'sistema_nombre' => config('constants.NOMBRE_SISTEMA', 'Sistema de Gestión de Turnos - UNSa')
        ];
    }

    /**
     * Construye el diccionario de reemplazo a partir de una reserva real o array
     */
    public function buildVariablesDictionary($reserva, $extraVars = [])
    {
        if (is_array($reserva)) {
            $vars = $reserva;
        } elseif ($reserva instanceof Turnos_Dependencias_Reservas) {
            $tramiteNombre = 'Trámite Solicitado';
            $dependenciaNombre = 'Universidad Nacional de Salta';

            if ($reserva->turno_horario && $reserva->turno_horario->turno_tramite && $reserva->turno_horario->turno_tramite->tramite) {
                $tramite = $reserva->turno_horario->turno_tramite->tramite;
                $tramiteNombre = $tramite->nombre ?? $tramiteNombre;
                if ($tramite->dependencia) {
                    $dependenciaNombre = $tramite->dependencia->nombre ?? $dependenciaNombre;
                }
            }

            $fechaFormateada = $reserva->fecha ? Carbon::parse($reserva->fecha)->format('d/m/Y') : '';
            $horaFormateada = $reserva->hora ? ($reserva->hora . ' hs') : '';

            $vars = [
                'codigo' => $reserva->codigo ?? '',
                'nombre' => $reserva->nombre_apellido ?? '',
                'nombre_apellido' => $reserva->nombre_apellido ?? '',
                'dni' => $reserva->dni ?? '',
                'email' => $reserva->email ?? '',
                'telefono' => $reserva->celular ?? '',
                'celular' => $reserva->celular ?? '',
                'fecha' => $fechaFormateada,
                'hora' => $horaFormateada,
                'tramite' => $tramiteNombre,
                'dependencia' => $dependenciaNombre,
                'institucion' => $reserva->nombre_institucion ?? '',
                'nombre_institucion' => $reserva->nombre_institucion ?? '',
                'cargo' => $reserva->cargo_responsable ?? '',
                'cargo_responsable' => $reserva->cargo_responsable ?? '',
                'nivel' => $reserva->nivel_institucion ?? '',
                'nivel_institucion' => $reserva->nivel_institucion ?? '',
                'curso' => $reserva->curso_comision ?? '',
                'curso_comision' => $reserva->curso_comision ?? '',
                'cantidad_personas' => $reserva->cantidad_personas ? ($reserva->cantidad_personas . ' persona(s)') : '1 persona',
                'cantidad_acompanantes' => $reserva->cantidad_acompanantes ?? '0',
                'motivo_cancelacion' => $reserva->motivo_cancelacion ?? '',
                'anio_actual' => date('Y'),
                'sistema_nombre' => config('constants.NOMBRE_SISTEMA', 'Sistema de Gestión de Turnos - UNSa')
            ];
        } else {
            $vars = self::getDummyData($this->circuito);
        }

        if (!empty($extraVars) && is_array($extraVars)) {
            $vars = array_merge($vars, $extraVars);
        }

        // Formatear claves con llaves {clave}
        $formatted = [];
        foreach ($vars as $k => $v) {
            $formatted['{' . trim($k, '{}') . '}'] = (string) $v;
        }

        return $formatted;
    }

    /**
     * Renderiza el asunto y el cuerpo sustituyendo los placeholders
     *
     * @param mixed $reserva Instancia de Turnos_Dependencias_Reservas o array
     * @param array $extraVars Variables adicionales opcionales
     * @return array ['asunto' => string, 'cuerpo_html' => string]
     */
    public function render($reserva = null, $extraVars = [])
    {
        $dictionary = $this->buildVariablesDictionary($reserva, $extraVars);

        $asunto = str_replace(array_keys($dictionary), array_values($dictionary), $this->asunto);
        $cuerpo = str_replace(array_keys($dictionary), array_values($dictionary), $this->cuerpo_html);

        return [
            'asunto' => $asunto,
            'cuerpo_html' => $cuerpo
        ];
    }

    /**
     * Obtiene una plantilla activa por clave, o retorna null
     */
    public static function getActiveByClave($clave)
    {
        return self::where('clave', $clave)->where('activo', true)->first();
    }

    /**
     * Obtiene las plantillas predeterminadas de fábrica
     */
    public static function getDefaultTemplates()
    {
        return [
            'turno_confirmado_individual' => [
                'clave' => 'turno_confirmado_individual',
                'nombre' => 'Confirmación de Turno Individual',
                'circuito' => 'individual',
                'evento' => 'confirmacion',
                'asunto' => 'Confirmación de Turno - Código: {codigo}',
                'descripcion' => 'Notificación de turno confirmado y aprobado enviada al solicitante individual.',
                'variables_disponibles' => '{codigo}, {nombre}, {dni}, {email}, {celular}, {fecha}, {hora}, {tramite}, {dependencia}, {anio_actual}',
                'cuerpo_html' => self::getHtmlTemplateConfirmadoIndividual()
            ],
            'turno_cancelado_individual' => [
                'clave' => 'turno_cancelado_individual',
                'nombre' => 'Cancelación de Turno Individual',
                'circuito' => 'individual',
                'evento' => 'cancelacion',
                'asunto' => 'Cancelación de Turno - Código: {codigo}',
                'descripcion' => 'Notificación enviada cuando un turno individual es cancelado por el usuario o por la administración.',
                'variables_disponibles' => '{codigo}, {nombre}, {dni}, {fecha}, {hora}, {tramite}, {dependencia}, {motivo_cancelacion}, {anio_actual}',
                'cuerpo_html' => self::getHtmlTemplateCanceladoIndividual()
            ],
            'turno_solicitado_individual' => [
                'clave' => 'turno_solicitado_individual',
                'nombre' => 'Solicitud de Turno Individual (Pendiente)',
                'circuito' => 'individual',
                'evento' => 'solicitud',
                'asunto' => 'Solicitud de Turno Registrada - Código: {codigo}',
                'descripcion' => 'Notificación informativa enviada al registrar el turno desde el portal web en estado pendiente.',
                'variables_disponibles' => '{codigo}, {nombre}, {dni}, {fecha}, {hora}, {tramite}, {dependencia}, {anio_actual}',
                'cuerpo_html' => self::getHtmlTemplateSolicitadoIndividual()
            ],
            'turno_confirmado_colegio' => [
                'clave' => 'turno_confirmado_colegio',
                'nombre' => 'Confirmación de Turno - Colegios / Escuelas',
                'circuito' => 'colegio',
                'evento' => 'confirmacion',
                'asunto' => 'Confirmación de Reserva Institucional: {institucion} - Código: {codigo}',
                'descripcion' => 'Notificación oficial enviada al responsable del colegio o delegación con los detalles y recomendaciones de la visita.',
                'variables_disponibles' => '{codigo}, {nombre}, {institucion}, {cargo}, {nivel}, {curso}, {cantidad_personas}, {cantidad_acompanantes}, {fecha}, {hora}, {tramite}, {dependencia}, {anio_actual}',
                'cuerpo_html' => self::getHtmlTemplateConfirmadoColegio()
            ],
            'turno_cancelado_colegio' => [
                'clave' => 'turno_cancelado_colegio',
                'nombre' => 'Cancelación de Turno - Colegios / Escuelas',
                'circuito' => 'colegio',
                'evento' => 'cancelacion',
                'asunto' => 'Cancelación de Reserva Escolar: {institucion} - Código: {codigo}',
                'descripcion' => 'Notificación oficial enviada a las autoridades de la institución educativa ante la cancelación de su turno grupal.',
                'variables_disponibles' => '{codigo}, {nombre}, {institucion}, {cargo}, {nivel}, {curso}, {cantidad_personas}, {fecha}, {hora}, {tramite}, {dependencia}, {motivo_cancelacion}, {anio_actual}',
                'cuerpo_html' => self::getHtmlTemplateCanceladoColegio()
            ],
            'turno_solicitado_colegio' => [
                'clave' => 'turno_solicitado_colegio',
                'nombre' => 'Solicitud de Turno Escolar / Institucional',
                'circuito' => 'colegio',
                'evento' => 'solicitud',
                'asunto' => 'Solicitud de Reserva Escolar Recibida - {institucion} ({codigo})',
                'descripcion' => 'Constancia de recepción de solicitud para contingentes educativos en espera de confirmación y asignación.',
                'variables_disponibles' => '{codigo}, {nombre}, {institucion}, {cargo}, {nivel}, {curso}, {cantidad_personas}, {cantidad_acompanantes}, {fecha}, {hora}, {tramite}, {dependencia}, {anio_actual}',
                'cuerpo_html' => self::getHtmlTemplateSolicitadoColegio()
            ]
        ];
    }

    /**
     * Plantillas HTML base elegantes y profesionales
     */
    private static function getHtmlTemplateConfirmadoIndividual()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Turno</title>
</head>
<body style="font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f4f7fa; margin: 0; padding: 25px; color: #2d3748;">
    <div style="max-width: 620px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: #ffffff; padding: 30px 25px; text-align: center;">
            <div style="font-size: 40px; margin-bottom: 8px;">✓</div>
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">¡Turno Confirmado con Éxito!</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">Circuito de Atención Individual</p>
        </div>
        <div style="padding: 30px 25px;">
            <p style="font-size: 16px; margin: 0 0 15px 0;">Estimado/a <strong>{nombre}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #4a5568; margin-bottom: 20px;">
                Le informamos que su reserva de turno ha sido confirmada correctamente en nuestro sistema. A continuación encontrará los detalles de su cita:
            </p>
            
            <div style="background: #f8fafc; border-left: 5px solid #16a34a; border-radius: 8px; padding: 20px; margin-bottom: 25px; border-top: 1px solid #edf2f7; border-right: 1px solid #edf2f7; border-bottom: 1px solid #edf2f7;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #718096; width: 40%;"><strong>Código de Turno:</strong></td>
                        <td style="padding: 6px 0;"><span style="background: #1e3c72; color: #ffffff; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-family: monospace; font-size: 15px;">{codigo}</span></td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;"><strong>DNI / Documento:</strong></td>
                        <td style="padding: 6px 0; color: #1a202c; font-weight: 600;">{dni}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;"><strong>Fecha:</strong></td>
                        <td style="padding: 6px 0; color: #16a34a; font-weight: bold;">{fecha}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;"><strong>Hora de Atención:</strong></td>
                        <td style="padding: 6px 0; color: #16a34a; font-weight: bold;">{hora}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;"><strong>Trámite / Servicio:</strong></td>
                        <td style="padding: 6px 0; color: #1a202c;">{tramite}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;"><strong>Dependencia / Lugar:</strong></td>
                        <td style="padding: 6px 0; color: #1a202c;">{dependencia}</td>
                    </tr>
                </table>
            </div>

            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 15px; margin-bottom: 25px; color: #92400e; font-size: 13px; line-height: 1.5;">
                <strong>📌 Información importante:</strong> Por favor preséntese 10 minutos antes del horario indicado con su DNI y el código de reserva arriba mencionado.
            </div>

            <p style="font-size: 13px; color: #718096; margin: 0;">Si no puede asistir, le solicitamos cancelar su turno desde el portal para permitir que otra persona utilice dicho horario.</p>
        </div>
        <div style="background: #edf2f7; padding: 18px 25px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0;">
            <p style="margin: 0 0 5px 0; font-weight: 600;">{sistema_nombre}</p>
            <p style="margin: 0;">Este es un mensaje generado automáticamente, por favor no responda a esta casilla.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private static function getHtmlTemplateCanceladoIndividual()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelación de Turno</title>
</head>
<body style="font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f4f7fa; margin: 0; padding: 25px; color: #2d3748;">
    <div style="max-width: 620px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: #ffffff; padding: 30px 25px; text-align: center;">
            <div style="font-size: 40px; margin-bottom: 8px;">✕</div>
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">Turno Cancelado</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">Circuito de Atención Individual</p>
        </div>
        <div style="padding: 30px 25px;">
            <p style="font-size: 16px; margin: 0 0 15px 0;">Estimado/a <strong>{nombre}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #4a5568; margin-bottom: 20px;">
                Le notificamos que el turno asignado a su nombre ha sido <strong>cancelado</strong> en el sistema.
            </p>
            
            <div style="background: #f8fafc; border-left: 5px solid #dc2626; border-radius: 8px; padding: 20px; margin-bottom: 20px; border-top: 1px solid #edf2f7; border-right: 1px solid #edf2f7; border-bottom: 1px solid #edf2f7;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #718096; width: 40%;"><strong>Código de Turno:</strong></td>
                        <td style="padding: 6px 0; font-weight: bold; color: #1a202c;">{codigo}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;"><strong>Fecha y Hora:</strong></td>
                        <td style="padding: 6px 0; color: #1a202c;">{fecha} - {hora}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;"><strong>Trámite:</strong></td>
                        <td style="padding: 6px 0; color: #1a202c;">{tramite}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #718096;"><strong>Dependencia:</strong></td>
                        <td style="padding: 6px 0; color: #1a202c;">{dependencia}</td>
                    </tr>
                </table>
            </div>

            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 15px; margin-bottom: 25px; color: #991b1b; font-size: 13px; line-height: 1.5;">
                <strong>Motivo de la cancelación:</strong><br>
                <span>{motivo_cancelacion}</span>
            </div>

            <p style="font-size: 14px; line-height: 1.6; color: #4a5568;">
                El horario correspondiente ha sido re-habilitado. Si aún requiere atención, puede solicitar un nuevo turno ingresando a nuestro portal web.
            </p>
        </div>
        <div style="background: #edf2f7; padding: 18px 25px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0;">
            <p style="margin: 0 0 5px 0; font-weight: 600;">{sistema_nombre}</p>
            <p style="margin: 0;">Notificación automática del sistema de gestión de turnos.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private static function getHtmlTemplateSolicitadoIndividual()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Turno Registrada</title>
</head>
<body style="font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f4f7fa; margin: 0; padding: 25px; color: #2d3748;">
    <div style="max-width: 620px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; padding: 30px 25px; text-align: center;">
            <div style="font-size: 40px; margin-bottom: 8px;">📋</div>
            <h1 style="margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">Solicitud Registrada</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">Circuito de Atención Individual</p>
        </div>
        <div style="padding: 30px 25px;">
            <p style="font-size: 16px; margin: 0 0 15px 0;">Estimado/a <strong>{nombre}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #4a5568; margin-bottom: 20px;">
                Hemos recibido correctamente su solicitud de turno. Su número identificador es:
            </p>
            
            <div style="text-align: center; margin: 25px 0;">
                <div style="display: inline-block; background: #eff6ff; border: 2px dashed #3b82f6; border-radius: 10px; padding: 15px 30px;">
                    <span style="font-size: 13px; text-transform: uppercase; color: #1d4ed8; font-weight: bold; display: block; letter-spacing: 1px;">Código de Turno</span>
                    <span style="font-size: 26px; font-weight: bold; color: #1e40af; font-family: monospace;">{codigo}</span>
                </div>
            </div>

            <div style="background: #f8fafc; border-radius: 8px; padding: 18px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 5px 0; color: #718096; width: 40%;"><strong>Fecha Solicitada:</strong></td>
                        <td style="padding: 5px 0; color: #1e293b; font-weight: 600;">{fecha} a las {hora}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #718096;"><strong>Trámite:</strong></td>
                        <td style="padding: 5px 0; color: #1e293b;">{tramite}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #718096;"><strong>Dependencia:</strong></td>
                        <td style="padding: 5px 0; color: #1e293b;">{dependencia}</td>
                    </tr>
                </table>
            </div>

            <p style="font-size: 13px; color: #64748b; line-height: 1.5;">Puede consultar el estado de su turno en cualquier momento desde el portal web con su DNI y código asignado.</p>
        </div>
        <div style="background: #edf2f7; padding: 18px 25px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0;">
            <p style="margin: 0 0 5px 0; font-weight: 600;">{sistema_nombre}</p>
            <p style="margin: 0;">Universidad Nacional de Salta</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private static function getHtmlTemplateConfirmadoColegio()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva Institucional</title>
</head>
<body style="font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 25px; color: #1e293b;">
    <div style="max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.08); border: 1px solid #cbd5e1;">
        <!-- Banner Institucional Escolar -->
        <div style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: #ffffff; padding: 32px 25px; text-align: center;">
            <div style="font-size: 42px; margin-bottom: 6px;">🏫</div>
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Reserva Institucional Confirmada</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.92;">Circuito de Delegaciones Escolares y Contingentes Educativos</p>
        </div>
        
        <div style="padding: 32px 28px;">
            <p style="font-size: 16px; margin: 0 0 15px 0;">A las autoridades y responsables de <strong>{institucion}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 22px;">
                Nos complace confirmar que la visita / turno institucional solicitado ha sido <strong>aprobado y reservado formalmente</strong>. A continuación detallamos la información para la recepción del contingente:
            </p>
            
            <!-- Tarjeta Código Oficial -->
            <div style="background: #eef2ff; border-radius: 10px; border: 1px solid #c7d2fe; padding: 16px 20px; text-align: center; margin-bottom: 25px;">
                <span style="font-size: 12px; text-transform: uppercase; color: #4338ca; font-weight: bold; letter-spacing: 1.5px; display: block; margin-bottom: 4px;">Código Oficial de Delegación</span>
                <span style="font-size: 28px; font-weight: 800; color: #312e81; font-family: monospace; letter-spacing: 2px;">{codigo}</span>
            </div>

            <!-- Ficha Técnica del Contingente Educativo -->
            <div style="background: #f8fafc; border-left: 5px solid #4f46e5; border-radius: 8px; padding: 20px; margin-bottom: 25px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 14px 0; font-size: 15px; color: #312e81; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                    Datos de la Institución y del Grupo
                </h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; width: 42%;"><strong>Institución Educativa:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a; font-weight: bold;">{institucion}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Responsable a Cargo:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{nombre} ({cargo})</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Nivel Educativo:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{nivel}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Curso / División:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{curso}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Alumnos / Participantes:</strong></td>
                        <td style="padding: 6px 0; color: #4338ca; font-weight: bold;">{cantidad_personas}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Docentes Acompañantes:</strong></td>
                        <td style="padding: 6px 0; color: #4338ca; font-weight: bold;">{cantidad_acompanantes}</td>
                    </tr>
                </table>
            </div>

            <!-- Ficha de la Cita -->
            <div style="background: #f8fafc; border-left: 5px solid #10b981; border-radius: 8px; padding: 20px; margin-bottom: 25px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 14px 0; font-size: 15px; color: #065f46; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                    Horario y Ubicación de Atención
                </h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; width: 42%;"><strong>Fecha Programada:</strong></td>
                        <td style="padding: 6px 0; color: #047857; font-weight: 700; font-size: 15px;">{fecha}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Horario de Recepción:</strong></td>
                        <td style="padding: 6px 0; color: #047857; font-weight: 700; font-size: 15px;">{hora}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Actividad / Trámite:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a; font-weight: 600;">{tramite}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Dependencia / Sede:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{dependencia}</td>
                    </tr>
                </table>
            </div>

            <!-- Protocolo / Recomendaciones para contingentes -->
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 18px; margin-bottom: 25px; color: #92400e; font-size: 13px; line-height: 1.6;">
                <strong>📋 Recomendaciones para el día de la visita:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    <li>Llegar con 15 minutos de anticipación para coordinar el ingreso ordenado del grupo.</li>
                    <li>Presentar la nómina de estudiantes y docentes en formato digital o impreso.</li>
                    <li>Los docentes acompañantes son responsables de la supervisión permanente del contingente durante la estadía.</li>
                </ul>
            </div>

            <p style="font-size: 13px; color: #64748b; margin: 0;">Ante cualquier necesidad de reprogramación o consulta técnica, comuníquese con la dependencia con antelación.</p>
        </div>
        
        <div style="background: #e2e8f0; padding: 20px 25px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #cbd5e1;">
            <p style="margin: 0 0 4px 0; font-weight: 700; color: #334155;">{sistema_nombre}</p>
            <p style="margin: 0;">Dirección de Extensión y Gestión de Visitas Institucionales - UNSa</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private static function getHtmlTemplateCanceladoColegio()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelación de Turno Escolar</title>
</head>
<body style="font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 25px; color: #1e293b;">
    <div style="max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.08); border: 1px solid #cbd5e1;">
        <div style="background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%); color: #ffffff; padding: 30px 25px; text-align: center;">
            <div style="font-size: 42px; margin-bottom: 6px;">✕</div>
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Cancelación de Turno Escolar</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">Circuito de Delegaciones Escolares e Institucionales</p>
        </div>
        
        <div style="padding: 32px 28px;">
            <p style="font-size: 16px; margin: 0 0 15px 0;">A las autoridades de <strong>{institucion}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 22px;">
                Les informamos formalmente que la reserva de turno para su delegación escolar ha sido <strong>cancelada</strong> en el sistema.
            </p>
            
            <div style="background: #f8fafc; border-left: 5px solid #b91c1c; border-radius: 8px; padding: 20px; margin-bottom: 22px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; width: 40%;"><strong>Código de Reserva:</strong></td>
                        <td style="padding: 6px 0; font-weight: bold; color: #0f172a;">{codigo}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Institución:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{institucion}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Responsable:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{nombre} ({cargo})</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Fecha y Hora Programada:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{fecha} a las {hora}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Actividad:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{tramite}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Dependencia:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{dependencia}</td>
                    </tr>
                </table>
            </div>

            <!-- Motivo de cancelación -->
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 18px; margin-bottom: 25px; color: #991b1b; font-size: 13px; line-height: 1.6;">
                <strong>Motivo asentado de la cancelación:</strong><br>
                <span>{motivo_cancelacion}</span>
            </div>

            <p style="font-size: 14px; line-height: 1.6; color: #475569;">
                Los cupos correspondientes han sido liberados en la agenda. Para coordinar una nueva fecha o solicitar una reprogramación, pueden ingresar nuevamente al sistema o contactarse con la coordinación institucional.
            </p>
        </div>
        
        <div style="background: #e2e8f0; padding: 20px 25px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #cbd5e1;">
            <p style="margin: 0 0 4px 0; font-weight: 700; color: #334155;">{sistema_nombre}</p>
            <p style="margin: 0;">Gestión de Visitas Institucionales - UNSa</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private static function getHtmlTemplateSolicitadoColegio()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Reserva Escolar Recibida</title>
</head>
<body style="font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 25px; color: #1e293b;">
    <div style="max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.08); border: 1px solid #cbd5e1;">
        <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; padding: 30px 25px; text-align: center;">
            <div style="font-size: 42px; margin-bottom: 6px;">📑</div>
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Solicitud Escolar en Evaluación</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">Circuito de Delegaciones Escolares e Institucionales</p>
        </div>
        
        <div style="padding: 32px 28px;">
            <p style="font-size: 16px; margin: 0 0 15px 0;">Estimados responsables de <strong>{institucion}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 22px;">
                Hemos registrado formalmente su solicitud de turno institucional. Su contingente ha sido ingresado al circuito de evaluación con el siguiente identificador:
            </p>
            
            <div style="background: #f0f9ff; border-radius: 10px; border: 1px solid #bae6fd; padding: 16px 20px; text-align: center; margin-bottom: 25px;">
                <span style="font-size: 12px; text-transform: uppercase; color: #0369a1; font-weight: bold; letter-spacing: 1.5px; display: block; margin-bottom: 4px;">Código de Solicitud</span>
                <span style="font-size: 28px; font-weight: 800; color: #0c4a6e; font-family: monospace; letter-spacing: 2px;">{codigo}</span>
            </div>

            <div style="background: #f8fafc; border-left: 5px solid #0284c7; border-radius: 8px; padding: 20px; margin-bottom: 22px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; width: 40%;"><strong>Institución:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a; font-weight: bold;">{institucion}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Responsable:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{nombre} ({cargo})</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Fecha y Hora Solicitada:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{fecha} - {hora}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;"><strong>Integrantes:</strong></td>
                        <td style="padding: 6px 0; color: #0f172a;">{cantidad_personas} (Acompañantes: {cantidad_acompanantes})</td>
                    </tr>
                </table>
            </div>

            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #1e40af; font-size: 13px; line-height: 1.5;">
                ℹ️ El equipo de la dependencia revisará la disponibilidad de guías y espacios para el cupo solicitado. Una vez confirmada, recibirá un correo con el comprobante definitivo.
            </div>
        </div>
        
        <div style="background: #e2e8f0; padding: 20px 25px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #cbd5e1;">
            <p style="margin: 0 0 4px 0; font-weight: 700; color: #334155;">{sistema_nombre}</p>
            <p style="margin: 0;">Universidad Nacional de Salta</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
