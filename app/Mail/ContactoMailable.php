<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function build()
    {
        if (isset($this->datos['tipo']) && $this->datos['tipo'] === 'inscripcion') {
            return $this->subject('¡Te has inscrito correctamente!')
                        ->view('inscripcion')  // Vista de inscripción
                        ->with('datos', $this->datos);
        }
        return $this->subject($this->datos['subject'])
                    ->view('contacto');
    }
}

