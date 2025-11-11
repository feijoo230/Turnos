<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NuevoUsuario extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    var $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;   
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //remitente
        $this->from('mesagralentradas@unsa.edu.ar', config('constants.NOMBRE_SISTEMA', 'Laravel').'Sistema de Información de Concursos UNSa')
        //asunto
        ->subject('CREACION DE USUARIO - '.config('constants.NOMBRE_SISTEMA', 'Laravel'))
        ->view('emails.alta_usuario', array('datos' => $this->datos));
    }
}

