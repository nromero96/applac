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

        // Array de PDFs
        $pdfs = [
            'Newark, NJ|Santo Domingo' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004602_Newark_Santo_Domingo.pdf',
            'Newark, NJ|Manzanillo' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004603_Newark_Manzanillo.pdf',
            'Newark, NJ|Cartagena' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004604_Newark_Cartagena.pdf',
            'Baltimore, MD|Santo Domingo' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004605_Baltimore_Santo_Domingo.pdf',
            'Baltimore, MD|Manzanillo' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004606_Baltimore_Manzanillo.pdf',
            'Baltimore, MD|Cartagena' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004607_Baltimore_Cartagena.pdf',
            'Jacksonville, FL|Santo Domingo' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004608_Jacksonville_Santo_Domingo.pdf',
            'Jacksonville, FL|Manzanillo' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004609_Jacksonville_Manzanillo.pdf',
            'Jacksonville, FL|Cartagena' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004610_Jacksonville_Cartagena.pdf',
            'Freeport, TX|Manzanillo' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004611_Freeport_Manzanillo.pdf',
            'Newark, NJ|Puerto Cortes' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004612_Newark_Puerto_Cortes.pdf',
            'Newark, NJ|Puerto Limon' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004613_Newark_Puerto_Limon.pdf',
            'Newark, NJ|Santo Tomas' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004614_Newark_Santo_Tomas.pdf',
            'Baltimore, MD|Puerto Cortes' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004615_Baltimore_Puerto_Cortes.pdf',
            'Baltimore, MD|Puerto Limon' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004616_Baltimore_Puerto_Limon.pdf',
            'Baltimore, MD|Santo Tomas' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004617_Baltimore_Santo_Tomas.pdf',
            'Freeport, TX|Puerto Cortes' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004618_Freeport_Puerto_Cortes.pdf',
            'Freeport, TX|Puerto Limon' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004619_Freeport_Puerto_Limon.pdf',
            'Freeport, TX|Santo Tomas' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004620_Freeport_Santo_Tomas.pdf',
            'Jacksonville, FL|Puerto Cortes' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004641_Jacksonville_Puerto_Cortes.pdf',
            'Jacksonville, FL|Puerto Limon' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004642_Jacksonville_Puerto_Limon.pdf',
            'Jacksonville, FL|Santo Tomas' => 'https://app.latinamericancargo.com/storage/uploads/Quote_00004643_Jacksonville_Santo_Tomas.pdf',
        ];

        // Combinación de puertos de la cotización actual
        $port_combination = $this->quotation->origin_airportorport . '|' . $this->quotation->destination_airportorport;

        $package_type = '';

        // Verificar si $this->cargoDetails existe y no está vacío
        if (!empty($this->cargoDetails) && count($this->cargoDetails) === 1) {
            $package_type = isset($this->cargoDetails[0]['package_type']) ? $this->cargoDetails[0]['package_type'] : '';
        }
        
        // Inicializar $pdf como null por defecto
        $pdf = null;

        if ($this->quotation->mode_of_transport == 'RoRo' && $this->quotation->cargo_type == 'Personal Vehicle' && ($package_type == 'Automobile' || $package_type == 'Motorcycle (crated or palletized) / ATV')) {
            $contviewblade = 'emails.quotation_created_personal_vehicle_shipping';
        
            // Verificar si la combinación de puertos tiene un PDF asociado y asignarlo a la variable $pdf
            if (isset($pdfs[$port_combination])) {
                $pdf = [
                    'url' => $pdfs[$port_combination],
                    'mime_type' => 'application/pdf',
                ];
            }
        } else {
            $contviewblade = 'emails.quotation_created';
        }

        $content = view($contviewblade, [
            'origin_country_name' => $origin_country_name,
            'destination_country_name' => $destination_country_name,
            'origin_state_name' => $origin_state_name,
            'destination_state_name' => $destination_state_name,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'quotation' => $this->quotation,
            'cargoDetails' => $this->cargoDetails,
        ])->render();


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
