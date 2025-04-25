<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\helpers;

use Illuminate\Support\Facades\Log;

//model country
use App\Models\Country;
use App\Models\State;
use App\Models\QuotationNote;

class QuoteUnqualifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quote;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quote, $customer_name, $email)
    {
        //
        $this->quote = $quote;
        $this->customer_name = $customer_name;
        $this->email = $email; // Pasamos el email del usuario
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $content = view('emails.quotation_unqualified', [
            'quote' => $this->quote,
            'customer_name' => $this->customer_name,
        ])->render();

        // Llama a tu función sendMailApi para enviar el correo
        sendMailApiLac(
            $this->email, 
            'Quote ID: #'. $this->quote->quotation_id .' - Update on your quote request', 
            $content,
            null,
            [],
            [], //copias
            [] //copia oculta
        );

        return $this->html($content);

    }
}
