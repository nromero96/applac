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

class WebQuotationCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quotation, $reguser, $email, $quotation_documents)
    {
        //
        $this->quotation = $quotation;
        $this->reguser = $reguser;
        $this->email = $email; // Pasamos el email del usuario
        $this->quotation_documents = $quotation_documents; // Pasamos el array de documentos de la cotización
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // Buscar el nombre del país de origen
        $origin_country = Country::find($this->quotation->origin_country_id);
        // Buscar el nombre del país de destino
        $destination_country = Country::find($this->quotation->destination_country_id);
        // Buscar el nombre del location reguser
        $reguser_location = Country::find($this->reguser->location);

        // Obtener el nombre del país, suponiendo que tengas un campo "name" en tu modelo Country
        $origin_country_name = $origin_country ? $origin_country->name : 'Unknown Country';
        $destination_country_name = $destination_country ? $destination_country->name : 'Unknown Country';
        $reguser_location_name = $reguser_location ? $reguser_location->name : 'Unknown Country';

        $contviewblade = 'emails.web_quotation_created';

        $content = view($contviewblade, [
            'origin_country_name' => $origin_country_name,
            'destination_country_name' => $destination_country_name,
            'reguser_location_name' => $reguser_location_name,
            'quotation' => $this->quotation,
            'reguser' => $this->reguser,
            'quotation_documents' => $this->quotation_documents,
        ])->render();

        // Llama a tu función sendMailApi para enviar el correo
        sendMailApiLac(
            $this->email, 
            'Quote ID: #'. $this->quotation->id .' - Your Request with Latin American Cargo - ['. $origin_country_name .' - '. $destination_country_name .'].', 
            $content,
            [], 
            [config('services.copymail.mail_1'), config('services.copymail.mail_2')]);

        // Retorna la vista utilizando la variable $content
        return $this->html($content);
    }
}
