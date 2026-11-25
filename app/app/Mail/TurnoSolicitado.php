<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Turnos_Dependencias_Reservas;
use App\Models\PlantillaEmail;

class TurnoSolicitado extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;

    public function __construct(Turnos_Dependencias_Reservas $reserva)
    {
        $this->reserva = $reserva;
    }

    public function build()
    {
        $fromEmail = config('mail.from.address', 'turnos@unsa.edu.ar');
        $fromName = config('mail.from.name', 'Sistema de Turnos UNSa');

        $tramite = ($this->reserva->turno_horario && $this->reserva->turno_horario->turno_tramite)
            ? $this->reserva->turno_horario->turno_tramite->tramite
            : null;

        $esColegio = !empty($this->reserva->nombre_institucion)
            || ($tramite && $tramite->usaCupoPorDelegacion());

        $clave = $esColegio ? 'turno_solicitado_colegio' : 'turno_solicitado_individual';
        $plantilla = PlantillaEmail::getActiveByClave($clave);

        if ($plantilla) {
            $rendered = $plantilla->render($this->reserva);
            return $this->from($fromEmail, $fromName)
                ->subject($rendered['asunto'])
                ->html($rendered['cuerpo_html']);
        }

        $subject = $esColegio
            ? 'Solicitud de Reserva Escolar Registrada - ' . $this->reserva->codigo
            : 'Solicitud de Turno Registrada - ' . $this->reserva->codigo;

        return $this->from($fromEmail, $fromName)
            ->subject($subject)
            ->view('emails.turno_solicitado', ['reserva' => $this->reserva]);
    }
}
