<?php
use GuzzleHttp\Client;
use Carbon\Carbon;
//Quotation use
use App\Models\Quotation;
use App\Models\User;
use App\Models\GuestUser;
use App\Models\Setting;

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
        ];
    }
}

if (!function_exists('scope_countries')) {
    function scope_countries()
    {
        return ['7', '9', '10', '12', '16', '19', '22', '24', '26', '30', '38', '40', '43', '47', '52', '55', '60', '61', '63', '65', '76', '87', '88', '90', '94', '95', '97', '108', '138', '142', '147', '154', '158', '169', '171', '172', '177', '184', '185', '187', '208', '221', '225', '231', '233', '237', '239', '240'];
    }
}

if (!function_exists('special_countries')) {
    function special_countries()
    {
        return ['38', '231'];
    }
}

//quotations calculate rating and assign to user
if (!function_exists('rateQuotation')) {

    function rateQuotation($quotation_id) {

        $quotation = \App\Models\Quotation::find($quotation_id);
        
        if (!$quotation) {
            throw new \Exception("Quotation not found");
        }

        //obtener cargoDetails
        $cargoDetails = \App\Models\CargoDetail::where('quotation_id', $quotation_id)->get();

        $rating = 0;

        $quotationmodeoftransport = $quotation->mode_of_transport;
        $cargotype = $quotation->cargo_type;

        $fecha_solicitud = Carbon::parse($quotation->created_at)->startOfDay();

        $catorcediasdespues = $fecha_solicitud->copy()->addDays(14); // 14 días desde la fecha de solicitud
        $treintadiasdespues = $fecha_solicitud->copy()->addDays(30); // 30 días desde la fecha de solicitud

        //######## Correo electrónico :::::::::::
        $email = $quotation->customer_user_id
        ? \App\Models\User::find($quotation->customer_user_id)->email
        : \App\Models\GuestUser::find($quotation->guest_user_id)->email;

        $domain = substr(strrchr($email, "@"), 1);

        $personal_domains = personal_domains();

        $package_type = '';

        // Verificar si cargoDetails existe y no está vacío
        if (!empty($cargoDetails) && count($cargoDetails) === 1) {
            $package_type = isset($cargoDetails[0]['package_type']) ? $cargoDetails[0]['package_type'] : '';
        }

        if($quotation->mode_of_transport == 'RoRo' && $quotation->cargo_type == 'Personal Vehicle' && ($package_type == 'Automobile' || $package_type == 'Motorcycle (crated or palletized) / ATV')){ 
            $rating += 1;
        } else {
            //######## Fecha de envío :::::::::::
            if ($quotation->shipping_date) {
                $fecha_envio = Carbon::parse(explode(' to ', $quotation->shipping_date)[0]);

                if ($fecha_envio->between($fecha_solicitud, $catorcediasdespues)) {
                    $rating += 1;  //1 a 14 días desde la fecha solicitud
                } elseif ($fecha_envio->between($catorcediasdespues, $treintadiasdespues)){
                    $rating += 0.5; //Desde el día 15 al 30 desde la fecha solicitud
                } elseif ($fecha_envio->gt($treintadiasdespues)){
                    $rating += 0; //Más de 30 días desde la fecha solicitud
                }
            }

            //######## Origen/Destino, Ubicación y correo :::::::::::
            $scopeCountries = scope_countries(); // Países en el scope
            $specialCountries = special_countries(); // Países especiales

            $location = $quotation->customer_user_id ? \App\Models\User::find($quotation->customer_user_id)->location : \App\Models\GuestUser::find($quotation->guest_user_id)->location;

            $isLocationInSpecialCountries = in_array($location, $specialCountries);

            $isOriginInScope = in_array($quotation->origin_country_id, $scopeCountries);
            $isDestinationInScope = in_array($quotation->destination_country_id, $scopeCountries);
            $isBusinessEmailAndNotEdu = !in_array($domain, $personal_domains) && !preg_match('/\.edu(\.[a-z]{2,})?$/', $domain);

            //scope exclude $specialCountries
            $scopeExcludeSpecialCountries = array_diff($scopeCountries, $specialCountries);
            //Location dentro del Scope $specialCountries
            $isLocationInScopeExcludeSpecialCountries = in_array($location, $scopeExcludeSpecialCountries);

            if ($isLocationInSpecialCountries) {
                if ($isBusinessEmailAndNotEdu) {
                    if ($isOriginInScope && $isDestinationInScope) {
                        $rating += 3;  // Tanto origen como destino están en el scope y el correo es de una empresa
                    } elseif ($isOriginInScope || $isDestinationInScope) {
                        $rating += 2; // Origen o destino están en el scope y el correo es de una empresa
                    } else {
                        $rating += 1; // Cualquier país fuera del scope
                    }
                }
            // Si la ubicación está en los países del scope excluyendo los especiales
            } elseif ($isLocationInScopeExcludeSpecialCountries && $isBusinessEmailAndNotEdu) {
                if (in_array($quotation->origin_country_id, $specialCountries) && in_array($quotation->destination_country_id, $scopeExcludeSpecialCountries)) {
                    $rating += 2; // Origen en specialCountries y destino en scopeExcludeSpecialCountries y el correo es de una empresa
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
        }

        // Guarda la calificación en la cotización
        $quotation->rating = $rating;
        $quotation->save();

        //si asignar a usuario si el rating es menor o igual a 5
        if($rating <= 5){
            // Obtener el valor de users_auto_assigned_quotes de la tabla settings
            $users_auto_assigned_quotes = \App\Models\Setting::where('key', 'users_auto_assigned_quotes')->first()->value;
            $userIds = json_decode($users_auto_assigned_quotes);


            // Buscar si la cotización es con el mismo email
            if (in_array($domain, $personal_domains)) {

                // Buscar el usuario con el correo electrónico proporcionado
                $user = \App\Models\User::where('email', $email)->first();
                $guestUser = \App\Models\GuestUser::where('email', $email)->first();

                // Inicializar variables para almacenar las cotizaciones
                $quotationWithUser = null;
                $quotationWithGuest = null;

                if ($user) {
                    $quotationWithUser = \App\Models\Quotation::where('customer_user_id', $user->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->orderBy('id', 'desc') // Ordenar por ID descendente
                        ->first(); // Obtener la última cotización
                }

                // Buscar la última cotización basada en el guest_user_id
                if ($guestUser) {
                    $quotationWithGuest = \App\Models\Quotation::where('guest_user_id', $guestUser->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->orderBy('id', 'desc') // Ordenar por ID descendente
                        ->first(); // Obtener la última cotización
                }

                // Decidir cuál cotización usar
                if ($quotationWithUser) {
                    // Si existe una cotización para el usuario, utilízala
                    $quotation->assigned_user_id = $quotationWithUser->assigned_user_id;
                    $quotation->save();
                    return $rating;
                } elseif ($quotationWithGuest) {
                    // Si no hay cotización para el usuario pero sí para el invitado, utilízala
                    $quotation->assigned_user_id = $quotationWithGuest->assigned_user_id;
                    $quotation->save();
                    return $rating;
                }

            }

            if (!in_array($domain, $personal_domains)) {

                // Buscar la última cotización con el mismo dominio
                $lastUserQuotationWithDomain = \App\Models\Quotation::join('users as user', 'quotations.customer_user_id', '=', 'user.id')
                    ->where('user.email', 'like', "%@$domain")
                    ->whereNotNull('quotations.assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                    ->orderBy('quotations.id', 'desc') // Ordenar por ID descendente
                    ->select('quotations.assigned_user_id')
                    ->first();

                // Buscar el último invitado registrado con el mismo dominio
                $lastGuestQuotationWithDomain = \App\Models\Quotation::join('guest_users as guest', 'quotations.guest_user_id', '=', 'guest.id')
                    ->where('guest.email', 'like', "%@$domain")
                    ->whereNotNull('quotations.assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                    ->orderBy('quotations.id', 'desc') // Ordenar por ID descendente
                    ->select('quotations.assigned_user_id')
                    ->first();

                if($lastUserQuotationWithDomain){
                    $quotation->assigned_user_id = $lastUserQuotationWithDomain->assigned_user_id;
                    $quotation->save();
                    return $rating;
                } elseif($lastGuestQuotationWithDomain){
                    $quotation->assigned_user_id = $lastGuestQuotationWithDomain->assigned_user_id;
                    $quotation->save();
                    return $rating;
                }

            }

            //ver si la cotización cumple con 4 y 5 rating
            if($rating >= 4){
                $userQuotationFourRatingCounts = [];

                $now = Carbon::now();
                $yesterday = $now->copy()->subDay();


                foreach ($userIds as $userId) {
                    $userQuotationFourRatingCounts[$userId] = \App\Models\Quotation::where('assigned_user_id', $userId)
                    ->where('rating', '>=', 4)
                    ->whereBetween('created_at', [$yesterday, $now])
                    ->count();
                }
                $minCountFourRating = min($userQuotationFourRatingCounts);
                $usersWithMinCountFourRating = array_filter($userQuotationFourRatingCounts, function($count) use ($minCountFourRating) {
                    return $count == $minCountFourRating;
                });
                $minUserIdFourRating = array_rand($usersWithMinCountFourRating);
                $quotation->assigned_user_id = $minUserIdFourRating;

                $quotation->save();
                return $rating;
            } else if($rating < 4){

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

        //######## Business Type :::::::::::
        if($business_role){
            if($business_role == 'Manufacturer'){
                $rating += 1;
            }elseif($business_role == 'Import / Export Business'){
                $rating += 1;
            }elseif($business_role == 'Retailer / Distributor'){
                $rating += 0.5;
            }elseif($business_role == 'Logistics Company / Freight Forwarder'){
                $rating += 0;
            }elseif($business_role == 'Individual / Private Person'){
                $rating += 0;
            }elseif($business_role == 'Other'){
                $rating += 0.5;
            }
        }

        //######## Shipment ready date :::::::::::
        if ($quotation->shipment_ready_date) {
            if($quotation->shipment_ready_date == 'Ready to ship now'){
                $rating += 1;
            }elseif($quotation->shipment_ready_date == 'Ready within 1-3 months'){
                $rating += 0.5;
            }elseif($quotation->shipment_ready_date == 'Not yet ready, just exploring options/budgeting'){
                $rating += 0;
            }else{
                $rating += 0;
            }
        }

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
            }else{
                $rating += 0;
            }
        }



        //######## Origen/Destino, Ubicación y correo :::::::::::
        $scopeCountries = scope_countries(); // Países en el scope
        $specialCountries = special_countries(); // Países especiales

        $location = $quotation->customer_user_id ? User::find($quotation->customer_user_id)->location : GuestUser::find($quotation->guest_user_id)->location;

        $isLocationInSpecialCountries = in_array($location, $specialCountries);

        $isOriginInScope = in_array($quotation->origin_country_id, $scopeCountries);
        $isDestinationInScope = in_array($quotation->destination_country_id, $scopeCountries);
        $isBusinessEmailAndNotEdu = !in_array($domain, $personal_domains) && !preg_match('/\.edu(\.[a-z]{2,})?$/', $domain);

        //scope exclude $specialCountries
        $scopeExcludeSpecialCountries = array_diff($scopeCountries, $specialCountries);
        //Location dentro del Scope $specialCountries
        $isLocationInScopeExcludeSpecialCountries = in_array($location, $scopeExcludeSpecialCountries);

        if ($isLocationInSpecialCountries) {
            if ($isBusinessEmailAndNotEdu) {
                if ($isOriginInScope && $isDestinationInScope) {
                        $rating += 2;  // Tanto origen como destino están en el scope y el correo es de una empresa
                } elseif ($isOriginInScope || $isDestinationInScope) {
                        $rating += 1; // Origen o destino están en el scope y el correo es de una empresa
                } else {
                        $rating += 0.5; // Cualquier país fuera del scope
                }
            }
            // Si la ubicación está en los países del scope excluyendo los especiales
        } elseif ($isLocationInScopeExcludeSpecialCountries && $isBusinessEmailAndNotEdu) {
            if (in_array($quotation->origin_country_id, $specialCountries) && in_array($quotation->destination_country_id, $scopeExcludeSpecialCountries)) {
                $rating += 1; // Origen en specialCountries y destino en scopeExcludeSpecialCountries y el correo es de una empresa
            }
        }

        // Guarda la calificación en la cotización
        $quotation->rating = $rating;
        $quotation->save();

        //si asignar a usuario si el rating es menor o igual a 5
        if($rating <= 5){
            // Obtener el valor de users_auto_assigned_quotes de la tabla settings
            $users_auto_assigned_quotes = Setting::where('key', 'users_auto_assigned_quotes')->first()->value;
            $userIds = json_decode($users_auto_assigned_quotes);


            // Buscar si la cotización es con el mismo email
            if (in_array($domain, $personal_domains)) {

                // Buscar el usuario con el correo electrónico proporcionado
                $user = User::where('email', $email)->first();
                $guestUser = GuestUser::where('email', $email)->first();

                // Inicializar variables para almacenar las cotizaciones
                $quotationWithUser = null;
                $quotationWithGuest = null;

                if ($user) {
                    $quotationWithUser = Quotation::where('customer_user_id', $user->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->orderBy('id', 'desc') // Ordenar por ID descendente
                        ->first(); // Obtener la última cotización
                }

                // Buscar la última cotización basada en el guest_user_id
                if ($guestUser) {
                    $quotationWithGuest = Quotation::where('guest_user_id', $guestUser->id)
                        ->whereNotNull('assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                        ->orderBy('id', 'desc') // Ordenar por ID descendente
                        ->first(); // Obtener la última cotización
                }

                // Decidir cuál cotización usar
                if ($quotationWithUser) {
                    // Si existe una cotización para el usuario, utilízala
                    $quotation->assigned_user_id = $quotationWithUser->assigned_user_id;
                    $quotation->save();
                    return $rating;
                } elseif ($quotationWithGuest) {
                    // Si no hay cotización para el usuario pero sí para el invitado, utilízala
                    $quotation->assigned_user_id = $quotationWithGuest->assigned_user_id;
                    $quotation->save();
                    return $rating;
                }

            }

            if (!in_array($domain, $personal_domains)) {

                // Buscar la última cotización con el mismo dominio
                $lastUserQuotationWithDomain = Quotation::join('users as user', 'quotations.customer_user_id', '=', 'user.id')
                    ->where('user.email', 'like', "%@$domain")
                    ->whereNotNull('quotations.assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                    ->orderBy('quotations.id', 'desc') // Ordenar por ID descendente
                    ->select('quotations.assigned_user_id')
                    ->first();

                // Buscar el último invitado registrado con el mismo dominio
                $lastGuestQuotationWithDomain = Quotation::join('guest_users as guest', 'quotations.guest_user_id', '=', 'guest.id')
                    ->where('guest.email', 'like', "%@$domain")
                    ->whereNotNull('quotations.assigned_user_id') // Asegúrate de que assigned_user_id no sea nulo
                    ->orderBy('quotations.id', 'desc') // Ordenar por ID descendente
                    ->select('quotations.assigned_user_id')
                    ->first();

                if($lastUserQuotationWithDomain){
                    $quotation->assigned_user_id = $lastUserQuotationWithDomain->assigned_user_id;
                    $quotation->save();
                    return $rating;
                } elseif($lastGuestQuotationWithDomain){
                    $quotation->assigned_user_id = $lastGuestQuotationWithDomain->assigned_user_id;
                    $quotation->save();
                    return $rating;
                }

            }

            //ver si la cotización cumple con 4 y 5 rating
            if($rating >= 4){
                $userQuotationFourRatingCounts = [];

                $now = Carbon::now();
                $yesterday = $now->copy()->subDay();


                foreach ($userIds as $userId) {
                    $userQuotationFourRatingCounts[$userId] = Quotation::where('assigned_user_id', $userId)
                    ->where('rating', '>=', 4)
                    ->whereBetween('created_at', [$yesterday, $now])
                    ->count();
                }
                $minCountFourRating = min($userQuotationFourRatingCounts);
                $usersWithMinCountFourRating = array_filter($userQuotationFourRatingCounts, function($count) use ($minCountFourRating) {
                    return $count == $minCountFourRating;
                });
                $minUserIdFourRating = array_rand($usersWithMinCountFourRating);
                $quotation->assigned_user_id = $minUserIdFourRating;

                $quotation->save();
                return $rating;
            } else if($rating < 4){

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
