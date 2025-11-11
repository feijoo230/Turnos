<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NuevoTramite extends Mailable
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
        $email = $this->from('mesagralentradas@unsa.edu.ar', 'MESA DE ENTRADAS VIRTUAL de la UNSa')
        //asunto
        ->subject('CONFIRMACION DE REGISTRO DE TRAMITE')
        ->view('emails.alta_tramite', array('datos' => $this->datos));
        //adjuntamos los archivos
        foreach($this->datos['files'] as $file){
            $email->attach($file['path'], [
                        'as' => $file['as'],
                        'mime' => $file['mime'],
                    ]);
        }

        return $email;
    }
}

