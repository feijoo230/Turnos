<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Turnos_Dependencias_Reservas;

class TurnoConfirmado extends Mailable
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

        return $this->from($fromEmail, $fromName)
            ->subject('Confirmación de Reserva de Turno - ' . $this->reserva->codigo)
            ->view('emails.turno_confirmado', ['reserva' => $this->reserva]);
    }
}
