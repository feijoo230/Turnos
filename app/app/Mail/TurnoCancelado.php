<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Turnos_Dependencias_Reservas;
use App\Models\PlantillaEmail;

class TurnoCancelado extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;

    /**
     * Create a new message instance.
     *
     * @param Turnos_Dependencias_Reservas $reserva
     * @return void
     */
    public function __construct(Turnos_Dependencias_Reservas $reserva)
    {
        $this->reserva = $reserva;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromEmail = config('mail.from.address', 'turnos@unsa.edu.ar');
        $fromName = config('mail.from.name', 'Sistema de Turnos UNSa');

        // Detectar si pertenece al circuito de colegios/institucional o individual
        $esColegio = !empty($this->reserva->nombre_institucion)
            || (bool) $this->reserva->es_grupal
            || ($this->reserva->turno_horario && $this->reserva->turno_horario->turno_tramite && optional($this->reserva->turno_horario->turno_tramite->tramite)->tipo_modalidad === 'institucional');

        $clave = $esColegio ? 'turno_cancelado_colegio' : 'turno_cancelado_individual';
        $plantilla = PlantillaEmail::getActiveByClave($clave);

        if ($plantilla) {
            $rendered = $plantilla->render($this->reserva);
            return $this->from($fromEmail, $fromName)
                ->subject($rendered['asunto'])
                ->html($rendered['cuerpo_html']);
        }

        // Fallback por defecto
        $subject = $esColegio
            ? 'Cancelación de Reserva Escolar - ' . ($this->reserva->nombre_institucion ?? $this->reserva->codigo)
            : 'Cancelación de Turno - ' . $this->reserva->codigo;

        return $this->from($fromEmail, $fromName)
            ->subject($subject)
            ->view('emails.turno_cancelado', ['reserva' => $this->reserva]);
    }
}
