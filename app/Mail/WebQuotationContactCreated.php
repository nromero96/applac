<?php

namespace App\Mail;

use App\Models\QuotationDocument;
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
        $quotation_documents = QuotationDocument::where('quotation_id', $this->data['inquiry']['id'])->get();
        $content = view('emails.web.quotation_contact_created', [
            'data' => $this->data,
            'quotation_documents' => $quotation_documents,
        ])->render();

        $emails_to = [];
        if (!config('app.debug')) {
            $emails_to = [
                // config('services.copymail.mail_1'),
                config('services.copymail.mail_2'),
                'nicholas.herrera@lacship.com',
                'stephanie.buitrago@lacship.com',
                'fredy.arias@lacship.com',
                'brian.carrillo@lacship.com',
                'cris.valencia@lacship.com',
                'juan.carlos@lacship.com',
                'felipe.munoz@lacship.com',
            ];
        }

        sendMailApiLac(
            $this->data['response']['email'],
            'Request ID: #'. $this->data['inquiry']['id'] .' - Your Request with Latin American Cargo',
            $content,
            null,
            [],
            [], //copias
            $emails_to //copia oculta
        );

        return $this->html($content);
    }
}
