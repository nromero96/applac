<?php

use App\Enums\TypeDepartment;
use App\Enums\TypeNetwork;
use App\Enums\TypePriorityInq;
use App\Enums\TypeProcessFor;
use App\Enums\TypeStatus;
use GuzzleHttp\Client;
use Carbon\Carbon;
//Quotation use
use App\Models\Quotation;
use App\Models\User;
use App\Models\GuestUser;
use App\Models\Setting;
use App\Models\QuotationNote;
use App\Models\CargoDetail;
use App\Models\QuotePendingEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

function sendMailApiLac($toEmail, $subject, $content, $from = null, $attachments, $ccEmails = [], $bccEmails = [])
{
    $client = new Client();

    $personalizations = [
        [
            'to' => [
                ['email' => $toEmail],
            ],
        ],
    ];

    foreach ($ccEmails as $ccEmail) {
        $personalizations[0]['cc'][] = [
            'email' => $ccEmail,
        ];
    }

    foreach ($bccEmails as $bccEmail) {
        $personalizations[0]['bcc'][] = [
            'email' => $bccEmail,
        ];
    }

    // Si se proporciona un `from`, usarlo; de lo contrario, tomar el del .env el predeterminado
    $fromEmail = $from['email'] ?? config('services.sendgrid.sender_email');
    $fromName = $from['name'] ?? config('services.sendgrid.sender_name');

    $data = [
        'personalizations' => $personalizations,
        'from' => [
            'email' => $fromEmail,
            'name' => $fromName,
        ],
        'subject' => $subject,
        'content' => [
            [
                'type' => 'text/html',
                'value' => $content,
            ],
        ],
    ];

    // Adjuntar archivos
    if (!empty($attachments)) {
        foreach ($attachments as $attachment) {
            $data['attachments'][] = [
                'content' => base64_encode(file_get_contents($attachment['url'])),
                'filename' => basename($attachment['url']),
                'type' => $attachment['mime_type'],
                'disposition' => 'attachment',
            ];
        }
    }

    $response = $client->post('https://api.sendgrid.com/v3/mail/send', [
        'headers' => [
            'Authorization' => 'Bearer ' . config('services.sendgrid.api_key'),
            'Content-Type' => 'application/json',
        ],
        'json' => $data,
    ]);

    if ($response->getStatusCode() == 202) {
        return '1';
    } else {
        return '0';
    }
}


if (!function_exists('personal_domains')) {
    function personal_domains()
    {
        return [
            'gmail.com',
            'yahoo.com', 'ymail.com', 'yahoo.es', 'yahoo.co.uk', 'yahoo.fr', 'yahoo.de', 'yahoo.it', 'yahoo.ca', 'yahoo.com.mx',
            'outlook.com', 'hotmail.com', 'live.com',
            'aol.com',
            'msn.com',
            'icloud.com',
            'hotmail.es',
            'proton.me',
            'pm.me',
            'fastmail.net',
            'live.ca',
            'me.com'
        ];
    }
}

//Scope Países LATAM Y CARIBE
if (!function_exists('scope_countries')) {
    function scope_countries()
    {
        return ['7', '9', '10', '12', '16', '19', '22', '24', '26', '30', '40', '43', '47', '52', '55', '60', '61', '63', '65', '76', '87', '88', '90', '94', '95', '97', '108', '138', '142', '147', '154', '158', '169', '171', '172', '177', '184', '185', '187', '208', '221', '225', '233', '237', '239', '240'];
    }
}

//US/CA Países ESPECIALES
if (!function_exists('special_countries')) {
    function special_countries()
    {
        return ['38', '231'];
    }
}

//Europe Países EUROPA
if(!function_exists('europe_countries')) {
    function europe_countries()
    {
        return ['2', '5', '11', '14', '15', '20', '21', '27', '33', '54', '56', '57', '58', '68', '74', '75', '81', '82', '84', '85', '99', '100', '105', '107', '112', '120', '125', '126', '127', '129', '135', '144', '145', '155', '164', '175', '176', '180', '181', '189', '193', '197', '198', '205', '211', '212', '223', '228', '230', '236', '244'];
    }
}

//Other Resto de países
if(!function_exists('other_countries')) {
    function other_countries()
    {
        return ['1', '3', '4', '6', '8', '13', '17', '18', '23', '25', '28', '29', '31', '32', '34', '35', '36', '37', '39', '41', '42', '45', '46', '48', '49', '50', '51', '53', '59', '62', '64', '66', '67', '69', '70', '71', '72', '73', '77', '78', '79', '80', '83', '86', '89', '91', '92', '93', '96', '98', '101', '102', '103', '104', '106', '109', '110', '111', '113', '114', '115', '116', '117', '118', '119', '121', '122', '123', '124', '128', '130', '131', '132', '133', '134', '136', '137', '139', '140', '141', '143', '146', '148', '149', '150', '151', '152', '153', '156', '157', '159', '160', '161', '162', '163', '165', '166', '167', '168', '170', '173', '174', '178', '179', '182', '183', '186', '188', '190', '191', '192', '194', '195', '196', '199', '200', '201', '202', '203', '204', '206', '207', '209', '210', '213', '214', '215', '216', '217', '218', '219', '220', '222', '224', '226', '227', '229', '232', '234', '235', '238', '241', '242', '243', '245', '246'];
    }
}

//China
if(!function_exists('china_country')) {
    function china_country()
    {
        return ['44'];
    }
}


//quotations calculate rating and assign to user
if (!function_exists('rateQuotation')) {

    function rateQuotation($quotation_id) {

        $quotation = Quotation::find($quotation_id);

        if (!$quotation) {
            throw new \Exception("Quotation not found");
        }

        //obtener cargoDetails
        $cargoDetails = CargoDetail::where('quotation_id', $quotation_id)->get();

        $rating = 0;

        $quotationmodeoftransport = $quotation->mode_of_transport;
        $cargotype = $quotation->cargo_type;

        $fecha_solicitud = Carbon::parse($quotation->created_at)->startOfDay();

        $catorcediasdespues = $fecha_solicitud->copy()->addDays(14); // 14 días desde la fecha de solicitud
        $veintiunadiasdespues = $fecha_solicitud->copy()->addDays(21); // 21 días desde la fecha de solicitud
        $treintadiasdespues = $fecha_solicitud->copy()->addDays(30); // 30 días desde la fecha de solicitud

        //######## customer_type :::::::::::
        $customer_type = $quotation->customer_user_id
        ? User::find($quotation->customer_user_id)->customer_type
        : GuestUser::find($quotation->guest_user_id)->customer_type;

        //######## Correo electrónico :::::::::::
        $email = $quotation->customer_user_id
        ? User::find($quotation->customer_user_id)->email
        : GuestUser::find($quotation->guest_user_id)->email;

        $domain = substr(strrchr($email, "@"), 1);
        $personal_domains = personal_domains();

        $package_type = '';

        // Verificar si cargoDetails existe y no está vacío
        if (!empty($cargoDetails) && count($cargoDetails) === 1) {
            $package_type = isset($cargoDetails[0]['package_type']) ? $cargoDetails[0]['package_type'] : '';
        }


        //######## Origen/Destino, Ubicación y correo :::::::::::
        $scopeCountries = scope_countries(); // Países en el scope
        $specialCountries = special_countries(); // Países especiales
        $europeCountries = europe_countries(); // Países en Europa
        $otherCountries = other_countries(); // Resto de países
        $chinaCountry = china_country(); // China


        //Location
        $location = $quotation->customer_user_id ? \App\Models\User::where('id', $quotation->customer_user_id)->value('location') : ($quotation->guest_user_id ? \App\Models\GuestUser::where('id', $quotation->guest_user_id)->value('location') : null);

        $isLocationInSpecialCountries = in_array($location, $specialCountries);
        $isLocationInEuropeCountries = in_array($location, $europeCountries);
        $isLocationScopeCountries = in_array($location, $scopeCountries);
        $isLocationInOtherCountries = in_array($location, $otherCountries);
        $isLocationChina = in_array($location, $chinaCountry);

        //Origen
        $isOriginInSpecialCountries = in_array($quotation->origin_country_id, $specialCountries);
        $isOriginInEuropeCountries = in_array($quotation->origin_country_id, $europeCountries);
        $isOriginInScopeCountries = in_array($quotation->origin_country_id, $scopeCountries);
        $isOriginInOtherCountries = in_array($quotation->origin_country_id, $otherCountries);
        $isOriginChina = in_array($quotation->origin_country_id, $chinaCountry);

        //Destino
        $isDestinationInSpecialCountries = in_array($quotation->destination_country_id, $specialCountries);
        $isDestinationInEuropeCountries = in_array($quotation->destination_country_id, $europeCountries);
        $isDestinationInScopeCountries = in_array($quotation->destination_country_id, $scopeCountries);
        $isDestinationInOtherCountries = in_array($quotation->destination_country_id, $otherCountries);
        $isDestinationChina = in_array($quotation->origin_country_id, $chinaCountry);

        //Correo de empresa y no de educación
        $isBusinessEmailAndNotEdu = !in_array($domain, $personal_domains) && !preg_match('/\.edu(\.[a-z]{2,})?$/', $domain);

        if($customer_type == 'Business'){
            //######## Fecha de envío :::::::::::
                if ($quotation->shipping_date && $quotation->no_shipping_date == 'no') {
                    $fecha_envio = Carbon::parse(explode(' to ', $quotation->shipping_date)[0]);
                    if ($fecha_envio->between($fecha_solicitud, $catorcediasdespues)) {
                        $rating += 1;  //1 a 14 días desde la fecha solicitud
                    } elseif ($fecha_envio->between($catorcediasdespues, $treintadiasdespues)){
                        $rating += 0.5; //Desde el día 15 al 30 desde la fecha solicitud
                    } elseif ($fecha_envio->gt($treintadiasdespues)){
                        $rating += 0; //Más de 30 días desde la fecha solicitud
                    }
                }

            //######## Valor ::::::::::::
                if($cargotype == 'LCL' || $cargotype == 'LTL' || $quotationmodeoftransport == 'Air'){
                    if ($quotation->declared_value > 2500) {
                        $rating += 1;
                    }
                } else if($cargotype == 'FCL' || $cargotype == 'FTL' || $quotationmodeoftransport == 'RoRo'){
                    if ($quotation->declared_value > 25000) {
                        $rating += 1;
                    }
                } else if($quotationmodeoftransport == 'Breakbulk'){
                    if ($quotation->declared_value > 60000) {
                        $rating += 1;
                    }
                }

            //######## Mail, Location, Origen y Destino :::::::::::
                if($isBusinessEmailAndNotEdu){
                    if ($isLocationInSpecialCountries) {
                        if(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Scope) o (Origin Scope -  Destination USA/CA)
                            $rating += 3;
                        } elseif($isOriginInScopeCountries && $isDestinationInScopeCountries){
                            //(Origin Scope - Destination Scope)
                            $rating += 2;
                        } elseif($isOriginInEuropeCountries && $isDestinationInScopeCountries){
                            //(Origin Europe - Destination Scope)
                            $rating += 2;
                        } elseif($isOriginInEuropeCountries && $isDestinationInSpecialCountries){
                            //(Origin Europe - Destination USA/CA)
                            $rating += 2;
                        } elseif(($isOriginInOtherCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInOtherCountries)){
                            //(Origin Other - Destination Scope) o (Origin Scope - Destination Other)
                            $rating += 2;
                        } elseif($isOriginInOtherCountries && $isDestinationInOtherCountries){
                            //(Origin Other - Destination Other)
                            $rating += 1;
                        } elseif(($isOriginInOtherCountries && $isDestinationInEuropeCountries) || ($isOriginInEuropeCountries && $isDestinationInOtherCountries)){
                            //(Origin Other - Destination Europe) o (Origin Europe - Destination Other)
                            $rating += 1;
                        } elseif($isOriginInEuropeCountries && $isDestinationInEuropeCountries){
                            //(Origin Europe - Destination Europe)
                            $rating += 1;
                        } elseif( $isOriginInSpecialCountries && $isDestinationInSpecialCountries){
                            //(Origin USA/CA - Destination USA/CA)
                            $rating += 2;
                        } else if ($isOriginChina && $isDestinationInSpecialCountries) {
                            //(Origin China - Destination USA/CA)
                            $rating += 2;
                        } elseif ( $isOriginInOtherCountries && $isDestinationInSpecialCountries) {
                            //(Origin Other - Destination USA/CA)
                            $rating += 2;
                        }
                    } elseif ($isLocationInEuropeCountries) {
                        if(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Scope) o (Origin Scope -  Destination USA/CA)
                            $rating += 2;
                        }elseif(($isOriginInSpecialCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInSpecialCountries)){
                            //((Origin USA/CA - Destination Other) o (Origin Other - Destination USA/CA))
                            $rating += 2;
                        }elseif(($isOriginInScopeCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInScopeCountries)){
                            //(Origin Scope - Destination Other) o (Origin Other - Destination Scope)
                            $rating += 2;
                        }elseif($isOriginInSpecialCountries && $isDestinationInEuropeCountries){
                            //(Origin USA/CA - Destination Europe)
                            $rating += 2;
                        }elseif($isOriginInScopeCountries && $isDestinationInEuropeCountries){
                            //(Origin Scope - Destination Europe)
                            $rating += 2;
                        }elseif($isOriginInOtherCountries && $isDestinationInEuropeCountries){
                            //(Origin Other - Destination Europe)
                            $rating += 2;
                        } else if ($isOriginChina && $isDestinationInEuropeCountries) {
                            //(Origin China - Destination europe)
                            $rating += 2;
                        }
                    } elseif ($isLocationScopeCountries) {
                        if($isOriginInSpecialCountries && $isDestinationInScopeCountries){
                            //(Origin USA/CA - Destination Scope)
                            $rating += 2;
                        } else if ($isOriginInEuropeCountries && $isDestinationInScopeCountries) {
                            //(Origin Europe - Destination Scope)
                            $rating += 2;
                        } else if ($isOriginChina && $isDestinationInScopeCountries) {
                            //(Origin China - Destination Scope)
                            $rating += 2;
                        } else if ($isOriginInOtherCountries && $isDestinationInScopeCountries) {
                            //(Origin Other - Destination Scope)
                            $rating += 2;
                        }
                    }
                }
        }elseif($customer_type == 'Logistics Company'){
            //######## Fecha de envío :::::::::::
                if ($quotation->shipping_date && $quotation->no_shipping_date == 'no') {
                    $fecha_envio = Carbon::parse(explode(' to ', $quotation->shipping_date)[0]);

                    if ($fecha_envio->between($fecha_solicitud, $catorcediasdespues)) {
                        $rating += 1;  //1 a 14 días desde la fecha solicitud
                    } elseif ($fecha_envio->between($catorcediasdespues, $treintadiasdespues)){
                        $rating += 0.5; //Desde el día 15 al 30 desde la fecha solicitud
                    } elseif ($fecha_envio->gt($treintadiasdespues)){
                        $rating += 0; //Más de 30 días desde la fecha solicitud
                    }
                }

            //######## Valor ::::::::::::
                if($cargotype == 'LCL' || $cargotype == 'LTL' || $quotationmodeoftransport == 'Air'){
                    if ($quotation->declared_value > 2500) {
                        $rating += 1;
                    }
                } else if($cargotype == 'FCL' || $cargotype == 'FTL' || $quotationmodeoftransport == 'RoRo'){
                    if ($quotation->declared_value > 25000) {
                        $rating += 1;
                    }
                } else if($quotationmodeoftransport == 'Breakbulk'){
                    if ($quotation->declared_value > 60000) {
                        $rating += 1;
                    }
                }

            //######## Mail, Location, Origen y Destino :::::::::::
                if($isBusinessEmailAndNotEdu){
                    if ($isLocationScopeCountries) {
                        if(($isOriginInSpecialCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Other) o (Origin Other -  Destination USA/CA)
                            $rating += 3;
                        } elseif(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Scope) O (Origin Scope - Destination USA/CA)
                            $rating += 3;
                        } elseif(($isOriginInSpecialCountries && $isDestinationInEuropeCountries) || ($isOriginInEuropeCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Europe) o (Origin Europe - Destination USA/CA)
                            $rating += 3;
                        }
                    } elseif ($isLocationInEuropeCountries) {
                        if(($isOriginInSpecialCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Other) o (Origin Other - Destination USA/CA)
                            $rating += 3;
                        }elseif(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Scope) o (Origin Scope -  Destination USA/CA)
                            $rating += 3;
                        }elseif(($isOriginInSpecialCountries && $isDestinationInEuropeCountries) || ($isOriginInEuropeCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Europe) o (Origin Europe - Destination USA/CA)
                            $rating += 3;
                        }
                    } elseif ($isLocationInOtherCountries) {
                        if(($isOriginInSpecialCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Other) o (Origin Other - Destination USA/CA)
                            $rating += 3;
                        }elseif(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Scope) o (Origin Scope -  Destination USA/CA)
                            $rating += 3;
                        }elseif(($isOriginInSpecialCountries && $isDestinationInEuropeCountries) || ($isOriginInEuropeCountries && $isDestinationInSpecialCountries)){
                            //(Origin USA/CA - Destination Europe) o (Origin Europe - Destination USA/CA)
                            $rating += 3;
                        }
                    }
                }
        }elseif($customer_type == 'Individual'){
            if($quotation->mode_of_transport == 'RoRo' && $quotation->cargo_type == 'Personal Vehicle' && ($package_type == 'Automobile' || $package_type == 'Motorcycle (crated or palletized) / ATV')){
                //######## Fecha de envío :::::::::::
                if ($quotation->shipping_date && $quotation->no_shipping_date == 'no') {
                    $fecha_envio = Carbon::parse(explode(' to ', $quotation->shipping_date)[0]);
                    if ($fecha_envio->between($fecha_solicitud, $veintiunadiasdespues)) {
                        $rating += 2;  //1 a 21 días desde la fecha solicitud
                    } elseif ($fecha_envio->gt($veintiunadiasdespues)){
                        $rating += 1; //Más de 22 días desde la fecha solicitud
                    }
                }

                if($quotation->no_shipping_date == 'yes'){
                    $rating += 1; //No shipping date
                }
            }
        }
        // Guarda la calificación en la cotización
        $quotation->rating = $rating;
        $quotation->save();

        // if($rating == 0 && !($quotation->mode_of_transport == 'RoRo' && $quotation->cargo_type == 'Personal Vehicle')){
        if($rating >= 0 && $rating <= 2.5 && !($quotation->mode_of_transport == 'RoRo' && $quotation->cargo_type == 'Personal Vehicle')){
            //Registrar QuotationNote
            QuotationNote::create([
                'quotation_id' => $quotation->id,
                'type' => 'inquiry_status',
                'action' => "'{$quotation->status}' to 'Unqualified'",
                'reason' => 'Low Rating Auto-Decline',
                'note' => 'Low Rating Request - Auto-Decline Email Sent',
                'user_id' => 1,
            ]);

            //Actualizar status de la cotización a 'Unqualified'
            $quotation->update([
                'status' => 'Unqualified',
                'updated_at' => Carbon::now(),
            ]);

            $customer_qt = $quotation->customer_user_id
                        ? User::find($quotation->customer_user_id)
                        : GuestUser::find($quotation->guest_user_id);

            $customer_name = trim(($customer_qt->name ?? '') . ' ' . ($customer_qt->lastname ?? ''));

            // Registrar en QuotePendingEmail para enviar email
            QuotePendingEmail::create([
                'quotation_id'  => $quotation->id,
                'type'          => 'Unqualified',
                'customer_name' => $customer_name,
                'email'         => $email,
                'status'        => 'pending',
            ]);
        }

        //si asignar a usuario si el rating es menor o igual a 5
        if($rating <= 5){
            // Obtener el valor de users_auto_assigned_quotes de la tabla settings
            $users_auto_assigned_quotes = Setting::where('key', 'users_auto_assigned_quotes')->first()->value;
            $userIds = json_decode($users_auto_assigned_quotes);

            $userIdsArray = array_map('intval', json_decode($users_auto_assigned_quotes));

            // Buscar si la cotización es con el mismo email
            if (in_array($domain, $personal_domains)) {
                // Buscar el usuario con el correo electrónico proporcionado
                $user = User::where('email', $email)->first();

                $guestUser = GuestUser::where('email', $email)
                            ->orderBy('id', 'desc') // Ordenar por ID descendente
                            ->offset(1) // <- Antepenúltimo GuestUser con ese email
                            ->limit(1)
                            ->first();

                // Inicializar variables para almacenar las cotizaciones
                $quotationWithUser = null;
                $quotationWithGuest = null;

                // Buscar la última cotización basada en el user_id
                if ($user) {
                    $quotationWithUser = Quotation::where('customer_user_id', $user->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->orderBy('id', 'desc') // Ordenar por ID descendente
                        ->first(); // Obtener la última cotización
                    if($quotationWithUser && in_array($quotationWithUser->assigned_user_id, $userIdsArray)){
                        $quotation->assigned_user_id = $quotationWithUser->assigned_user_id;
                        $quotation->save();
                        return $rating;
                    }
                }

                // Buscar la última cotización basada en el guest_user_id
                if ($guestUser) {
                    $quotationWithGuest = Quotation::where('guest_user_id', $guestUser->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->first();

                    if($quotationWithGuest && in_array($quotationWithGuest->assigned_user_id, $userIdsArray)){
                        $quotation->assigned_user_id = $quotationWithGuest->assigned_user_id;
                        $quotation->save();
                        return $rating;
                    }
                }
            }

            if (!in_array($domain, $personal_domains)) {

                $userWithDomain = User::where('email', 'like', "%@$domain")->first();

                $guestUserWithDomain = GuestUser::where('email', 'like', "%@$domain")
                            ->orderBy('id', 'desc') // Ordenar por ID descendente
                            ->offset(1) // <- Antepenúltimo GuestUser con ese email
                            ->limit(1)
                            ->first();

                // Inicializar variables para almacenar las cotizaciones
                $quotationWithUserDomain = null;
                $quotationWithGuestDomain = null;

                // Buscar la última cotización basada en el user_id
                if ($userWithDomain) {
                    $quotationWithUserDomain = Quotation::where('customer_user_id', $userWithDomain->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->orderBy('id', 'desc') // Ordenar por ID descendente
                        ->first(); // Obtener la última cotización
                    if($quotationWithUserDomain && in_array($quotationWithUserDomain->assigned_user_id, $userIdsArray)){
                        $quotation->assigned_user_id = $quotationWithUserDomain->assigned_user_id;
                        $quotation->save();
                        return $rating;
                    }
                }

                // Buscar la última cotización basada en el guest_user_id
                if ($guestUserWithDomain) {
                    $quotationWithGuestDomain = Quotation::where('guest_user_id', $guestUserWithDomain->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->first();

                    if($quotationWithGuestDomain && in_array($quotationWithGuestDomain->assigned_user_id, $userIdsArray)){
                        $quotation->assigned_user_id = $quotationWithGuestDomain->assigned_user_id;
                        $quotation->save();
                        return $rating;
                    }
                }
            }

            //ver si la cotización cumple con 4 y 5 rating
            if($rating >= 4){
                // usuarios temporalmente
                $stephanieId = 2733;
                $nicholasId  = 3;
                $counterFile = 'quotations_rating_4-5.txt';
                $counter = (int)Storage::get($counterFile);
                // Patrón S-S-S-S-N
                if ($counter < 4) {
                    $quotation->assigned_user_id = $stephanieId;
                } else {
                    $quotation->assigned_user_id = $nicholasId;
                }
                // Incrementar contador (0 a 4) y reiniciar
                $counter = ($counter + 1) % 5;
                Storage::put($counterFile, $counter);

                $quotation->save();
                return $rating;
                // $userQuotationFourRatingCounts = [];

                // $now = Carbon::now();
                // $yesterday = $now->copy()->subDay();

                // foreach ($userIds as $userId) {
                //     $userQuotationFourRatingCounts[$userId] = Quotation::where('assigned_user_id', $userId)
                //     ->where('rating', '>=', 4)
                //     ->whereBetween('created_at', [$yesterday, $now])
                //     ->count();
                // }
                // $minCountFourRating = min($userQuotationFourRatingCounts);
                // $usersWithMinCountFourRating = array_filter($userQuotationFourRatingCounts, function($count) use ($minCountFourRating) {
                //     return $count == $minCountFourRating;
                // });
                // $minUserIdFourRating = array_rand($usersWithMinCountFourRating);
                // $quotation->assigned_user_id = $minUserIdFourRating;

                // $quotation->save();
                // return $rating;
            } else {
            // } else if($rating < 4){
            // if ($rating >= 3 && $rating <= 3.5) {

                $indexFile = 'current_index.txt';
                $currentIndex = (int)Storage::get($indexFile);
                if ($currentIndex >= count($userIds)) {
                    $currentIndex = 0;
                }

                // Obtén el usuario en el índice actual
                $selectedUserId = $userIds[$currentIndex];

                $quotation->assigned_user_id = $selectedUserId;

                $currentIndex++;
                Storage::put($indexFile, $currentIndex);
            }

            $quotation->save();
        }

        return $rating;
    }
}


//web quotations calculate rating and assign to user
if (!function_exists('rateQuotationWeb')) {

    function rateQuotationWeb($quotation_id) {
        $quotation = Quotation::find($quotation_id);
        if (!$quotation) {
            throw new \Exception("Quotation not found");
        }

        $rating = 0;

        //######## email :::::::::::
        $email = $quotation->customer_user_id
        ? User::find($quotation->customer_user_id)->email
        : GuestUser::find($quotation->guest_user_id)->email;

        //######## business_role :::::::::::
        $business_role = $quotation->customer_user_id
        ? User::find($quotation->customer_user_id)->business_role
        : GuestUser::find($quotation->guest_user_id)->business_role;

        //######## ea_shipments :::::::::::
        $ea_shipments = $quotation->customer_user_id
        ? User::find($quotation->customer_user_id)->ea_shipments
        : GuestUser::find($quotation->guest_user_id)->ea_shipments;

        $domain = substr(strrchr($email, "@"), 1);
        $personal_domains = personal_domains();

        //######## Origen/Destino, Ubicación y correo :::::::::::
        $scopeCountries = scope_countries(); // Países en el scope
        $specialCountries = special_countries(); // Países especiales
        $europeCountries = europe_countries(); // Países en Europa
        $otherCountries = other_countries(); // Resto de países
        $chinaCountry = china_country(); // China


        //Location
        $location = $quotation->customer_user_id ? \App\Models\User::where('id', $quotation->customer_user_id)->value('location') : ($quotation->guest_user_id ? \App\Models\GuestUser::where('id', $quotation->guest_user_id)->value('location') : null);

        $isLocationInSpecialCountries = in_array($location, $specialCountries);
        $isLocationInEuropeCountries = in_array($location, $europeCountries);
        $isLocationScopeCountries = in_array($location, $scopeCountries);
        $isLocationInOtherCountries = in_array($location, $otherCountries);
        $isLocationChina = in_array($location, $chinaCountry);

        //Origen
        $isOriginInSpecialCountries = in_array($quotation->origin_country_id, $specialCountries);
        $isOriginInEuropeCountries = in_array($quotation->origin_country_id, $europeCountries);
        $isOriginInScopeCountries = in_array($quotation->origin_country_id, $scopeCountries);
        $isOriginInOtherCountries = in_array($quotation->origin_country_id, $otherCountries);
        $isOriginChina = in_array($quotation->origin_country_id, $chinaCountry);

        //Destino
        $isDestinationInSpecialCountries = in_array($quotation->destination_country_id, $specialCountries);
        $isDestinationInEuropeCountries = in_array($quotation->destination_country_id, $europeCountries);
        $isDestinationInScopeCountries = in_array($quotation->destination_country_id, $scopeCountries);
        $isDestinationInOtherCountries = in_array($quotation->destination_country_id, $otherCountries);
        $isDestinationChina = in_array($quotation->origin_country_id, $chinaCountry);

        //Correo de empresa y no de educación
        $isBusinessEmailAndNotEdu = !in_array($domain, $personal_domains) && !preg_match('/\.edu(\.[a-z]{2,})?$/', $domain);

        //######## Business Type :::::::::::
        if($business_role){
            $business_role_clean = explode(' - ', $business_role)[0];
            if($business_role_clean == 'Manufacturer' || $business_role_clean == 'Importer / Exporter (Owner of Goods)' || $business_role_clean == 'Retailer / Distributor' || $business_role_clean == 'Other'){
                //######## Shipment ready date :::::::::::
                    /*
                    if ($quotation->shipment_ready_date) {
                        if($quotation->shipment_ready_date == 'Ready to ship now'){
                            $rating += 1;
                        }elseif($quotation->shipment_ready_date == 'Ready within 1-3 months'){
                            $rating += 0.5;
                        }elseif($quotation->shipment_ready_date == 'Not yet ready, just exploring options/budgeting'){
                            $rating += 0;
                        }
                    }
                    */

                //######## Annual Shipments :::::::::::
                    if($ea_shipments){
                        if($ea_shipments == 'One-time shipment'){
                            $rating += 0;
                        }elseif($ea_shipments == 'Between 2-10'){
                            $rating += 0.5;
                        }elseif($ea_shipments == 'Between 11-50'){
                            $rating += 1;
                        }elseif($ea_shipments == 'Between 51-200'){
                            $rating += 1;
                        }elseif($ea_shipments == 'Between 201-500'){
                            $rating += 0.5;
                        }elseif($ea_shipments == 'More than 500'){
                            $rating += 0;
                        }
                    }

                //######## Mail, Location, Origen y Destino :::::::::::
                    if($isBusinessEmailAndNotEdu){
                        if ($isLocationInSpecialCountries) {
                            if(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Scope) o (Origin Scope -  Destination USA/CA)
                                $rating += 3;
                            } elseif($isOriginInScopeCountries && $isDestinationInScopeCountries){
                                //(Origin Scope - Destination Scope)
                                $rating += 2;
                            } elseif($isOriginInEuropeCountries && $isDestinationInScopeCountries){
                                //(Origin Europe - Destination Scope)
                                $rating += 2;
                            } elseif($isOriginInEuropeCountries && $isDestinationInSpecialCountries){
                                //(Origin Europe - Destination USA/CA)
                                $rating += 2;
                            } elseif(($isOriginInOtherCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInOtherCountries)){
                                //(Origin Other - Destination Scope) o (Origin Scope - Destination Other)
                                $rating += 2;
                            } elseif($isOriginInOtherCountries && $isDestinationInOtherCountries){
                                //(Origin Other - Destination Other)
                                $rating += 1;
                            } elseif(($isOriginInOtherCountries && $isDestinationInEuropeCountries) || ($isOriginInEuropeCountries && $isDestinationInOtherCountries)){
                                //(Origin Other - Destination Europe) o (Origin Europe - Destination Other)
                                $rating += 1;
                            } elseif($isOriginInEuropeCountries && $isDestinationInEuropeCountries){
                                //(Origin Europe - Destination Europe)
                                $rating += 1;
                            } elseif( $isOriginInSpecialCountries && $isDestinationInSpecialCountries){
                                //(Origin USA/CA - Destination USA/CA)
                                $rating += 2;
                            } else if ($isOriginChina && $isDestinationInSpecialCountries) {
                                //(Origin China - Destination USA/CA)
                                $rating += 2;
                            } elseif ( $isOriginInOtherCountries && $isDestinationInSpecialCountries) {
                                //(Origin Other - Destination USA/CA)
                                $rating += 2;
                            }
                        } elseif ($isLocationInEuropeCountries) {
                            if(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Scope) o (Origin Scope -  Destination USA/CA)
                                $rating += 2;
                            }elseif(($isOriginInSpecialCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInSpecialCountries)){
                                //((Origin USA/CA - Destination Other) o (Origin Other - Destination USA/CA))
                                $rating += 2;
                            }elseif(($isOriginInScopeCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInScopeCountries)){
                                //(Origin Scope - Destination Other) o (Origin Other - Destination Scope)
                                $rating += 2;
                            }elseif($isOriginInSpecialCountries && $isDestinationInEuropeCountries){
                                //(Origin USA/CA - Destination Europe)
                                $rating += 2;
                            }elseif($isOriginInScopeCountries && $isDestinationInEuropeCountries){
                                //(Origin Scope - Destination Europe)
                                $rating += 2;
                            }elseif($isOriginInOtherCountries && $isDestinationInEuropeCountries){
                                //(Origin Other - Destination Europe)
                                $rating += 2;
                            } else if ($isOriginChina && $isDestinationInEuropeCountries) {
                                //(Origin China - Destination europe)
                                $rating += 2;
                            }
                        } elseif ($isLocationScopeCountries) {
                            if($isOriginInSpecialCountries && $isDestinationInScopeCountries){
                                //(Origin USA/CA - Destination Scope)
                                $rating += 2;
                            } else if ($isOriginInEuropeCountries && $isDestinationInScopeCountries) {
                                //(Origin Europe - Destination Scope)
                                $rating += 2;
                            } else if ($isOriginChina && $isDestinationInScopeCountries) {
                                //(Origin China - Destination Scope)
                                $rating += 2;
                            } else if ($isOriginInOtherCountries && $isDestinationInScopeCountries) {
                                //(Origin Other - Destination Scope)
                                $rating += 2;
                            }
                        }
                    }

            }elseif($business_role_clean == 'Logistics Company / Freight Forwarder'){
                //######## Shipment ready date :::::::::::
                /*
                    if ($quotation->shipment_ready_date) {
                        if($quotation->shipment_ready_date == 'Ready to ship now'){
                            $rating += 1;
                        }elseif($quotation->shipment_ready_date == 'Ready within 1-3 months'){
                            $rating += 0.5;
                        }elseif($quotation->shipment_ready_date == 'Not yet ready, just exploring options/budgeting'){
                            $rating += 0;
                        }
                    }
                */

                //######## Annual Shipments :::::::::::
                    if($ea_shipments){
                        if($ea_shipments == 'One-time shipment'){
                            $rating += 0;
                        }elseif($ea_shipments == 'Between 2-10'){
                            $rating += 1;
                        }elseif($ea_shipments == 'Between 11-50'){
                            $rating += 1;
                        }elseif($ea_shipments == 'Between 51-200'){
                            $rating += 1;
                        }elseif($ea_shipments == 'Between 201-500'){
                            $rating += 0.5;
                        }elseif($ea_shipments == 'More than 500'){
                            $rating += 0;
                        }
                    }

                //######## Mail, Location, Origen y Destino :::::::::::
                    if($isBusinessEmailAndNotEdu){
                        if ($isLocationScopeCountries) {
                            if(($isOriginInSpecialCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Other) o (Origin Other -  Destination USA/CA)
                                $rating += 3;
                            } elseif(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Scope) O (Origin Scope - Destination USA/CA)
                                $rating += 3;
                            } elseif(($isOriginInSpecialCountries && $isDestinationInEuropeCountries) || ($isOriginInEuropeCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Europe) o (Origin Europe - Destination USA/CA)
                                $rating += 3;
                            }
                        } elseif ($isLocationInEuropeCountries) {
                            if(($isOriginInSpecialCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Other) o (Origin Other - Destination USA/CA)
                                $rating += 3;
                            }elseif(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Scope) o (Origin Scope -  Destination USA/CA)
                                $rating += 3;
                            }elseif(($isOriginInSpecialCountries && $isDestinationInEuropeCountries) || ($isOriginInEuropeCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Europe) o (Origin Europe - Destination USA/CA)
                                $rating += 3;
                            }
                        } elseif ($isLocationInOtherCountries) {
                            if(($isOriginInSpecialCountries && $isDestinationInOtherCountries) || ($isOriginInOtherCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Other) o (Origin Other - Destination USA/CA)
                                $rating += 3;
                            }elseif(($isOriginInSpecialCountries && $isDestinationInScopeCountries) || ($isOriginInScopeCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Scope) o (Origin Scope -  Destination USA/CA)
                                $rating += 3;
                            }elseif(($isOriginInSpecialCountries && $isDestinationInEuropeCountries) || ($isOriginInEuropeCountries && $isDestinationInSpecialCountries)){
                                //(Origin USA/CA - Destination Europe) o (Origin Europe - Destination USA/CA)
                                $rating += 3;
                            }
                        }
                    }

            }elseif($business_role_clean == 'Individual / Private Person'){
                //No hay rating
            }
        }

        $mode_of_transport = $quotation->mode_of_transport;
        $declared_value = $quotation->declared_value;

        $umbrales = [
            'FCL (Full Container Load)'   => 25000,
            'RORO (Roll-On/Roll-Off)'     => 25000,
            'FTL (Full Truckload)'        => 25000,
            'Bulk Liquid Transport'       => 25000,

            'LCL (Less-than-Container Load)' => 2500,
            'LTL (Less-than-Truckload)'      => 2500,
            'Standard Air Freight'           => 2500,

            'Breakbulk'                  => 60000,
            'Heavy Haul / Oversized'     => 60000,
            'Project Cargo'              => 60000,

            'Charter Flight'             => 100000,
        ];

        // Verificar si el modo de transporte tiene un threshold definido
        if (isset($umbrales[$mode_of_transport])) {
            $umbral = $umbrales[$mode_of_transport];
            $tolerance = $umbral * 0.10; // 10%

            if ($declared_value > $umbral) {
                $rating += 1;
            } elseif ($declared_value > ($umbral - $tolerance)) {
                $rating += 0.5;
            }
        }

        // Guarda la calificación en la cotización
        $quotation->rating = $rating;
        $quotation->save();

        if ($rating >= 0 && $rating <= 2.5) { // nueva regla
            //Registrar QuotationNote
            QuotationNote::create([
                'quotation_id' => $quotation->id,
                'type' => 'inquiry_status',
                'action' => "'{$quotation->status}' to 'Unqualified'",
                'reason' => 'Low Rating Auto-Decline',
                'note' => 'Low Rating Request - Auto-Decline Email Sent',
                'user_id' => 1,
            ]);

            //Actualizar status de la cotización a 'Unqualified'
            $quotation->update([
                'status' => 'Unqualified',
                'updated_at' => Carbon::now(),
            ]);

            $customer_qt = $quotation->customer_user_id
                        ? User::find($quotation->customer_user_id)
                        : GuestUser::find($quotation->guest_user_id);

            $customer_name = trim(($customer_qt->name ?? '') . ' ' . ($customer_qt->lastname ?? ''));

            // Registrar en QuotePendingEmail para enviar email
            QuotePendingEmail::create([
                'quotation_id'  => $quotation->id,
                'type'          => 'Unqualified',
                'customer_name' => $customer_name,
                'email'         => $email,
                'status'        => 'pending',
            ]);
        }

        //si asignar a usuario si el rating es menor o igual a 5
        if($rating <= 5){
            // Obtener el valor de users_auto_assigned_quotes de la tabla settings
            $users_auto_assigned_quotes = Setting::where('key', 'users_auto_assigned_quotes')->first()->value;
            $userIds = json_decode($users_auto_assigned_quotes);

            $userIdsArray = array_map('intval', json_decode($users_auto_assigned_quotes));

            // Buscar si la cotización es con el mismo email
            if (in_array($domain, $personal_domains)) {
                // Buscar el usuario con el correo electrónico proporcionado
                $user = User::where('email', $email)->first();

                $guestUser = GuestUser::where('email', $email)
                            ->orderBy('id', 'desc') // Ordenar por ID descendente
                            ->offset(1) // <- Antepenúltimo GuestUser con ese email
                            ->limit(1)
                            ->first();

                // Inicializar variables para almacenar las cotizaciones
                $quotationWithUser = null;
                $quotationWithGuest = null;

                // Buscar la última cotización basada en el user_id
                if ($user) {
                    $quotationWithUser = Quotation::where('customer_user_id', $user->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->orderBy('id', 'desc') // Ordenar por ID descendente
                        ->first(); // Obtener la última cotización
                    if($quotationWithUser && in_array($quotationWithUser->assigned_user_id, $userIdsArray)){
                        $quotation->assigned_user_id = $quotationWithUser->assigned_user_id;
                        $quotation->save();
                        return $rating;
                    }
                }

                // Buscar la última cotización basada en el guest_user_id
                if ($guestUser) {
                    $quotationWithGuest = Quotation::where('guest_user_id', $guestUser->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->first();

                    if($quotationWithGuest && in_array($quotationWithGuest->assigned_user_id, $userIdsArray)){
                        $quotation->assigned_user_id = $quotationWithGuest->assigned_user_id;
                        $quotation->save();
                        return $rating;
                    }
                }
            }

            if (!in_array($domain, $personal_domains)) {

                $userWithDomain = User::where('email', 'like', "%@$domain")->first();

                $guestUserWithDomain = GuestUser::where('email', 'like', "%@$domain")
                            ->orderBy('id', 'desc') // Ordenar por ID descendente
                            ->offset(1) // <- Antepenúltimo GuestUser con ese email
                            ->limit(1)
                            ->first();

                // Inicializar variables para almacenar las cotizaciones
                $quotationWithUserDomain = null;
                $quotationWithGuestDomain = null;

                // Buscar la última cotización basada en el user_id
                if ($userWithDomain) {
                    $quotationWithUserDomain = Quotation::where('customer_user_id', $userWithDomain->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->orderBy('id', 'desc') // Ordenar por ID descendente
                        ->first(); // Obtener la última cotización
                    if($quotationWithUserDomain && in_array($quotationWithUserDomain->assigned_user_id, $userIdsArray)){
                        $quotation->assigned_user_id = $quotationWithUserDomain->assigned_user_id;
                        $quotation->save();
                        return $rating;
                    }
                }

                // Buscar la última cotización basada en el guest_user_id
                if ($guestUserWithDomain) {
                    $quotationWithGuestDomain = Quotation::where('guest_user_id', $guestUserWithDomain->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->first();

                    if($quotationWithGuestDomain && in_array($quotationWithGuestDomain->assigned_user_id, $userIdsArray)){
                        $quotation->assigned_user_id = $quotationWithGuestDomain->assigned_user_id;
                        $quotation->save();
                        return $rating;
                    }
                }
            }

            //ver si la cotización cumple con 4 y 5 rating
            if($rating >= 4){
                // usuarios temporalmente
                $stephanieId = 2733;
                $nicholasId  = 3;
                $counterFile = 'quotations_rating_4-5.txt';
                $counter = (int)Storage::get($counterFile);
                // Patrón S-S-S-S-N
                if ($counter < 4) {
                    $quotation->assigned_user_id = $stephanieId;
                } else {
                    $quotation->assigned_user_id = $nicholasId;
                }
                // Incrementar contador (0 a 4) y reiniciar
                $counter = ($counter + 1) % 5;
                Storage::put($counterFile, $counter);

                $quotation->save();
                return $rating;
                // $userQuotationFourRatingCounts = [];
                // $now = Carbon::now();
                // $yesterday = $now->copy()->subDay();
                // foreach ($userIds as $userId) {
                //     $userQuotationFourRatingCounts[$userId] = Quotation::where('assigned_user_id', $userId)
                //     ->where('rating', '>=', 4)
                //     ->whereBetween('created_at', [$yesterday, $now])
                //     ->count();
                // }
                // $minCountFourRating = min($userQuotationFourRatingCounts);
                // $usersWithMinCountFourRating = array_filter($userQuotationFourRatingCounts, function($count) use ($minCountFourRating) {
                //     return $count == $minCountFourRating;
                // });
                // $minUserIdFourRating = array_rand($usersWithMinCountFourRating);
                // $quotation->assigned_user_id = $minUserIdFourRating;
                // $quotation->save();
                // return $rating;
            // } else if($rating < 4){
            // if ($rating >= 3 && $rating <= 3.5) {
            } else {

                $indexFile = 'current_index.txt';
                $currentIndex = (int)Storage::get($indexFile);
                if ($currentIndex >= count($userIds)) {
                    $currentIndex = 0;
                }

                // Obtén el usuario en el índice actual
                $selectedUserId = $userIds[$currentIndex];

                $quotation->assigned_user_id = $selectedUserId;

                $currentIndex++;
                Storage::put($indexFile, $currentIndex);
            }

            $quotation->save();

        }

        return $rating;
    }
}

//web quotations calculate rating and assign to user
if (!function_exists('rateQuotationAgentWeb')) {

    function rateQuotationAgentWeb($quotation_id) {
        $quotation = Quotation::find($quotation_id);
        if (!$quotation) {
            throw new \Exception("Quotation not found");
        }

        $rating = 0;

        //######## email :::::::::::
        $email = $quotation->customer_user_id
        ? User::find($quotation->customer_user_id)->email
        : GuestUser::find($quotation->guest_user_id)->email;

        //######## ea_shipments :::::::::::
        $ea_shipments = $quotation->customer_user_id
        ? User::find($quotation->customer_user_id)->ea_shipments
        : GuestUser::find($quotation->guest_user_id)->ea_shipments;

        $domain = substr(strrchr($email, "@"), 1);
        $personal_domains = personal_domains();

        //######## Origen/Destino, Ubicación y correo :::::::::::
        $scopeCountries = scope_countries(); // Países en el scope
        $specialCountries = special_countries(); // Países especiales
        $europeCountries = europe_countries(); // Países en Europa
        $otherCountries = other_countries(); // Resto de países
        $chinaCountry = china_country(); // China


        //Location
        $location = $quotation->customer_user_id ? \App\Models\User::where('id', $quotation->customer_user_id)->value('location') : ($quotation->guest_user_id ? \App\Models\GuestUser::where('id', $quotation->guest_user_id)->value('location') : null);

        $isLocationInSpecialCountries = in_array($location, $specialCountries);
        $isLocationInEuropeCountries = in_array($location, $europeCountries);
        $isLocationScopeCountries = in_array($location, $scopeCountries);
        $isLocationInOtherCountries = in_array($location, $otherCountries);
        $isLocationChina = in_array($location, $chinaCountry);

        //Origen
        $isOriginInSpecialCountries = in_array($quotation->origin_country_id, $specialCountries);
        $isOriginInEuropeCountries = in_array($quotation->origin_country_id, $europeCountries);
        $isOriginInScopeCountries = in_array($quotation->origin_country_id, $scopeCountries);
        $isOriginInOtherCountries = in_array($quotation->origin_country_id, $otherCountries);
        $isOriginChina = in_array($quotation->origin_country_id, $chinaCountry);

        //Destino
        $isDestinationInSpecialCountries = in_array($quotation->destination_country_id, $specialCountries);
        $isDestinationInEuropeCountries = in_array($quotation->destination_country_id, $europeCountries);
        $isDestinationInScopeCountries = in_array($quotation->destination_country_id, $scopeCountries);
        $isDestinationInOtherCountries = in_array($quotation->destination_country_id, $otherCountries);
        $isDestinationChina = in_array($quotation->origin_country_id, $chinaCountry);

        //Correo de empresa y no de educación
        $isBusinessEmailAndNotEdu = !in_array($domain, $personal_domains) && !preg_match('/\.edu(\.[a-z]{2,})?$/', $domain);

        //######## Shipment ready date :::::::::::
            if ($quotation->shipment_ready_date) {
                if($quotation->shipment_ready_date == 'Ready to ship now'){
                    $rating += 1;
                }elseif($quotation->shipment_ready_date == 'Ready within a month'){
                    $rating += 1;
                }elseif($quotation->shipment_ready_date == 'Ready within 1-3 months'){
                    $rating += 0.5;
                }elseif($quotation->shipment_ready_date == 'Not yet ready, just exploring options/budgeting'){
                    $rating += 0;
                }
                // Log::info('rating until -shipment ready- ' . $rating);
            }

        //######## Annual Shipments :::::::::::
            if($ea_shipments){
                if ($ea_shipments == 'One-time shipment') {
                    $rating += 0;
                } elseif($ea_shipments == 'Between 2-10') {
                    $rating += 0;
                } elseif($ea_shipments == 'Between 11-50') {
                    $rating += 1;
                } elseif($ea_shipments == 'Between 51-200') {
                    $rating += 1;
                } elseif($ea_shipments == 'Between 201-500') {
                    $rating += 2;
                } elseif($ea_shipments == 'More than 500') {
                    $rating += 2;
                }
                // Log::info('rating until -annual shipments- ' . $rating);
            }

        //######## Mail, Location, Origen y Destino :::::::::::
            if($isBusinessEmailAndNotEdu){
                if ($isLocationScopeCountries) {
                    if ($isOriginInSpecialCountries && $isDestinationInOtherCountries) {
                        // (Origin USA/CA - Destination Other)
                        $rating += 3;
                    } elseif ($isOriginInSpecialCountries && $isDestinationInScopeCountries) {
                        // (Origin USA/CA - Destination Scope)
                        $rating += 3;
                    } elseif ($isOriginInSpecialCountries && $isDestinationInEuropeCountries) {
                        // (Origin USA/CA - Destination Europe)
                        $rating += 3;
                    }
                } elseif ($isLocationInEuropeCountries) {
                    if ($isOriginInSpecialCountries && $isDestinationInOtherCountries) {
                        // (Origin USA/CA - Destination Other)
                        $rating += 3;
                    } elseif ($isOriginInSpecialCountries && $isDestinationInScopeCountries) {
                        // (Origin USA/CA - Destination Scope)
                        $rating += 3;
                    } elseif ($isOriginInSpecialCountries && $isDestinationInEuropeCountries) {
                        // (Origin USA/CA - Destination Europe)
                        $rating += 3;
                    }
                } elseif ($isLocationInSpecialCountries) {
                    if ($isOriginInSpecialCountries && $isDestinationInScopeCountries) {
                        // (Origin USA/CA - Destination Scope)
                        $rating += 1;
                    } elseif ($isOriginInSpecialCountries && $isDestinationInEuropeCountries) {
                        // (Origin USA/CA - Destination Europe)
                        $rating += 1;
                    } elseif ($isOriginInSpecialCountries && $isDestinationInOtherCountries) {
                        // (Origin USA/CA - Destination Other)
                        $rating += 1;
                    } elseif ($isOriginInSpecialCountries && $isDestinationChina) {
                        // (Origin USA/CA - Destination China)
                        $rating += 1;
                    }
                } elseif ($isLocationChina) {
                    if ($isOriginInSpecialCountries && $isDestinationInScopeCountries) {
                        // (Origin USA/CA - Destination Scope)
                        $rating += 2;
                    } elseif ($isOriginInSpecialCountries && $isDestinationInEuropeCountries) {
                        // (Origin USA/CA - Destination Europe)
                        $rating += 2;
                    } elseif ($isOriginInSpecialCountries && $isDestinationInOtherCountries) {
                        // (Origin USA/CA - Destination Other)
                        $rating += 2;
                    } elseif ($isOriginInSpecialCountries && $isDestinationChina) {
                        // (Origin USA/CA - Destination China)
                        $rating += 2;
                    }
                }
                // Log::info('rating until -locations- ' . $rating);
            }


        //###### Network
            $network = $quotation->customer_user_id ? \App\Models\User::where('id', $quotation->customer_user_id)->value('network') : ($quotation->guest_user_id ? \App\Models\GuestUser::where('id', $quotation->guest_user_id)->value('network') : null);
            if (isset($network[0])) {
                switch ($network[0]) {
                    case TypeNetwork::TWIG->value:
                    case TypeNetwork::WCA->value:
                        $rating += 2;
                        break;
                    case TypeNetwork::JC_TRANS->value:
                    case TypeNetwork::GKF->value:
                    case TypeNetwork::X2->value:
                    case TypeNetwork::PANGEA->value:
                    case TypeNetwork::GFA->value:
                    case TypeNetwork::DFA->value:
                        $rating += 1;
                        break;
                    case TypeNetwork::NONE->value:
                        $rating += 0;
                        break;
                    default:
                        break;
                }
            }
            // Log::info('rating until -network- ' . $rating);


        //###### Mode of transport
            $mode_of_transport = $quotation->mode_of_transport;
            $declared_value = $quotation->declared_value;

            $umbrales = [
                'FCL (Full Container Load)'         => 25000,
                'RORO (Roll-On/Roll-Off)'           => 25000,
                'FTL (Full Truckload)'              => 25000,
                'LCL (Less-than-Container Load)'    => 2500,
                'LTL (Less-than-Truckload)'         => 2500,
                'Standard Air Freight'              => 2500,
                'Breakbulk'                         => 60000,
                'Project Cargo'                     => 60000,
            ];

            // Verificar si el modo de transporte tiene un threshold definido
            if (isset($umbrales[$mode_of_transport])) {
                $umbral = $umbrales[$mode_of_transport];
                $tolerance = $umbral * 0.10; // 10%

                if ($declared_value > $umbral) {
                    $rating += 2;
                } elseif ($declared_value > ($umbral - $tolerance)) {
                    $rating += 1;
                }
            }
            // Log::info('rating until -transport/declared- ' . $rating);

        $priority = TypePriorityInq::LOW->value;
        if ($rating >= 6 and $rating <= 10) {
            $priority = TypePriorityInq::MEDIUM->value;
        }

        // Guarda la prioridad en la cotización
        $quotation->priority = $priority;
        $quotation->points = $rating;
        $quotation->save();


        // Asignar usuario basado en location
        $agent_users = User::where('department_id', TypeDepartment::AGENTS_DEPT->value)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Quoter');
            })
            ->get();
        $assigned_user_id = null;
        if ($agent_users) {
            foreach ($agent_users as $user) {
                if (in_array($location, $user->priority_countries) || in_array($location, $user->priority_countries_ext)) {
                    $assigned_user_id = $user->id;
                    break;
                }
            }
        }

        $quotation->assigned_user_id = $assigned_user_id;
        $quotation->save();

        return $rating;
    }
}


/**
 * with types
 */
if (!function_exists('type_network_labels')) {
    function type_network_labels($field) {
        $networks = is_string($field)
            ? json_decode($field, true)
            : $field;
        $labels = collect($networks)
            ->map(fn($network) => \App\Enums\TypeNetwork::from($network)->meta('label'))
            ->implode(', ');
        return $labels;
    }
}

if (!function_exists('type_network_pill_first')) {
    function type_network_pill_first($field) {
        if ($field) {
            $networks = is_string($field)
                ? json_decode($field, true)
                : $field;
            $pills = collect($networks)
                ->map(fn($network) => \App\Enums\TypeNetwork::from($network)->meta())
                ->toArray();

            if (sizeof($pills) > 0) {
                $first_pill = $pills[0];
            } else {
                return '';
            }

            $pill_draw = '';
            $pill_draw .= '<span
                class="__readinesss gap-1 d-flex align-items-center"
                style="color: '.$first_pill['color'].'; border: 1px solid'.$first_pill['color'] .'"
            >'
                . $first_pill['icon'] . $first_pill['short_label'] .
            '</span>';

            return $pill_draw;
        } else {
            return '';
        }
    }
}


if (!function_exists('auto_assign_processing')) {
    function auto_assign_processing($quotation_id) {
        $user_id = auth()->id();
        $dept_id = auth()->user()->department_id;
        $users = User::where('department_id', $dept_id)
            ->select('id', 'name', 'lastname')
            ->where('id', '!=', $user_id)
            ->where('status', 'active')
            ->whereHas('roles', function($q){
                $q->whereIn('role_id', [6]); // quoter
            })
            ->get()
            ;

        $result = Quotation::where(function($q) use ($users) {
                $q->whereIn('assigned_user_id', $users->pluck('id'));
                $q->orWhereIn('processed_by_user_id', $users->pluck('id'));
            })
            ->where('status', TypeStatus::QUALIFIED->value)
            ->where('id', '!=', $quotation_id)
            ->selectRaw("
                CASE
                    WHEN assigned_user_id IN (" . $users->pluck('id')->implode(',') . ")
                        THEN assigned_user_id
                    ELSE processed_by_user_id
                END as user_id,
                SUM(
                    CASE
                        WHEN process_for = '".TypeProcessFor::ESTIMATE->value."' THEN 1
                        WHEN process_for = '".TypeProcessFor::FULL_QUOTE->value."' THEN 3
                        ELSE 0
                    END
                ) as total_points,
                COUNT(id) as count_inquiries
            ")
            ->groupBy('user_id')
            ->orderBy('total_points', 'asc')
            ->orderBy('count_inquiries', 'asc')
            ->first()
            ;
        return $result->user_id;
    }
}
