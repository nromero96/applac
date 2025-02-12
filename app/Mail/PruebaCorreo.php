<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\helpers;

class PruebaCorreo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $toEmail = 'niltonromagu@gmail.com';
        $subject = 'Asunto del correo Listo';

    // Renderiza la vista 'emails.prueba' y obtén el contenido
    $content = view('emails.prueba')->render();

    // Llama a tu función sendMailApi desde Helpers para enviar el correo
    $response = sendMailApiLac($toEmail, $subject, $content, null, [config('services.copymail.mail_1')]);

    // Retorna la vista utilizando la variable $content
    return $this->html($content);
    }
}
