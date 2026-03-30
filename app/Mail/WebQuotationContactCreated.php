<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebQuotationContactCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        $content = view('emails.web.quotation_contact_created', [
            'data' => $this->data
        ])->render();

        sendMailApiLac(
            $this->data['response']['email'],
            'Quote ID: #'. $this->data['inquiry']['id'] .' - Your Request with Latin American Cargo',
            $content,
            null,
            [],
            [], //copias
            // [config('services.copymail.mail_1'), config('services.copymail.mail_2')] //copia oculta
            [
                // config('services.copymail.mail_1'),
                // config('services.copymail.mail_2'),
                // 'nicholas.herrera@lacship.com',
                // 'stephanie.buitrago@lacship.com',
                // 'fredy.arias@lacship.com',
                // 'brian.carrillo@lacship.com',
                // 'cris.valencia@lacship.com',
                // 'juan.carlos@lacship.com',
            ] //copia oculta
        );

        return $this->html($content);
    }
}
