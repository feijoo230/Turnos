<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Dependencia;

class EditarUsuario extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    var $data;
    var $dependencia;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //remitente
        $this->from(config('constants.EMAIL_NOTIFICACIONES', 'Laravel'),  config('constants.NOMBRE_SISTEMA', 'Laravel'))
        //asunto
        ->subject('ACTUALIZACIÓN DE USUARIO - '. config('constants.NOMBRE_SISTEMA', 'Laravel'))
        ->view('emails.editar_usuario', array('datos' => $this->data));
    }
}
