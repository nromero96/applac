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

class QuotationCreated extends Mailable{
    use Queueable, SerializesModels;

    public $quotation;

    public function __construct($quotation, $name, $lastname, $email, $cargoDetails){
        $this->quotation = $quotation;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->cargoDetails = $cargoDetails; // Pasamos el array de detalles de carga
    }

    public function build() {
        // Buscar el nombre del país de origen
        $origin_country = Country::find($this->quotation->origin_country_id);
        // Buscar el nombre del país de destino
        $destination_country = Country::find($this->quotation->destination_country_id);
        // Obtener el nombre del país, suponiendo que tengas un campo "name" en tu modelo Country
        $origin_country_name = $origin_country ? $origin_country->name : 'Unknown Country';
        $destination_country_name = $destination_country ? $destination_country->name : 'Unknown Country';

        $origin_state_name = '';
        $destination_state_name = '';
        //if origin_state_id is not null then find the state name
        if($this->quotation->origin_state_id != null){
            $origin_state = State::find($this->quotation->origin_state_id);
            //get name of state
            $origin_state_name = $origin_state ? $origin_state->name : 'Unknown State';
        }
        //if destination_state_id is not null then find the state name
        if($this->quotation->destination_state_id != null){
            $destination_state = State::find($this->quotation->destination_state_id);
            //get name of state
            $destination_state_name = $destination_state ? $destination_state->name : 'Unknown State';
        }

        // Renderiza la vista 'emails.quotation_created' y obtén el contenido
        $content = view('emails.quotation_created', [
            'origin_country_name' => $origin_country_name,
            'destination_country_name' => $destination_country_name,
            'origin_state_name' => $origin_state_name,
            'destination_state_name' => $destination_state_name,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'quotation' => $this->quotation,
            'cargoDetails' => $this->cargoDetails,
        ])->render();

        // Adjuntar el PDF
        if($this->quotation->mode_of_transport == 'RoRo'){
            if($this->quotation->cargo_type == 'Personal Vehicle'){
                $originportlist_lane1 = ['Newark, NJ', 'Baltimore, MD', 'Jacksonville, FL'];
                $destinationportlist_lane1 = ['Santo Domingo', 'Manzanillo', 'Cartagena'];

                $originportlist_lane2 = ['Freeport, TX'];
                $destinationportlist_lane2 = ['Manzanillo'];

                $originportlist_lane3 = ['Newark, NJ', 'Baltimore, MD', 'Jacksonville, FL'];
                $destinationportlist_lane3 = ['Puerto Cortes', 'Puerto Limon', 'Santo Tomas de Castilla'];

                $originportlist_lane4 = ['Freeport, TX'];
                $destinationportlist_lane4 = ['Puerto Cortes', 'Puerto Limon', 'Santo Tomas de Castilla'];

                if(in_array($this->quotation->origin_airportorport, $originportlist_lane1) && in_array($this->quotation->destination_airportorport, $destinationportlist_lane1)){
                    $pdf = [
                        'url' => 'https://app.latinamericancargo.com/storage/uploads/LAC_Lane_1_Quote.pdf',
                        'mime_type' => 'application/pdf',
                    ];
                } elseif(in_array($this->quotation->origin_airportorport, $originportlist_lane2) && in_array($this->quotation->destination_airportorport, $destinationportlist_lane2)){
                    $pdf = [
                        'url' => 'https://app.latinamericancargo.com/storage/uploads/LAC_Lane_2_Quote.pdf',
                        'mime_type' => 'application/pdf',
                    ];
                } elseif(in_array($this->quotation->origin_airportorport, $originportlist_lane3) && in_array($this->quotation->destination_airportorport, $destinationportlist_lane3)){
                    $pdf = [
                        'url' => 'https://app.latinamericancargo.com/storage/uploads/LAC_Lane_3_Quote.pdf',
                        'mime_type' => 'application/pdf',
                    ];
                } elseif(in_array($this->quotation->origin_airportorport, $originportlist_lane4) && in_array($this->quotation->destination_airportorport, $destinationportlist_lane4)){
                    $pdf = [
                        'url' => 'https://app.latinamericancargo.com/storage/uploads/LAC_Lane_4_Quote.pdf',
                        'mime_type' => 'application/pdf',
                    ];
                }else{
                    $pdf = null;
                }

            }else{
                $pdf = null;
            }
        }else{
            //no enviamos pdf
            $pdf = null;
        }

        // Llama a tu función sendMailApi para enviar el correo
        sendMailApiLac(
            $this->email, 
            'Quote ID #: '. $this->quotation->id .' - Your Request with Latin American Cargo - '. $this->quotation->mode_of_transport .' - ['. $origin_country_name .' - '. $destination_country_name .'].', 
            $content,
            ($pdf !== null) ? [$pdf] : [],
            [], 
            [config('services.copymail.mail_1'), config('services.copymail.mail_2')]);

        // Retorna la vista utilizando la variable $content
        return $this->html($content);

    }


}
