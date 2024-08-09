<?php
use GuzzleHttp\Client;
use Carbon\Carbon;

function sendMailApiLac($toEmail, $subject, $content, $attachments, $ccEmails = [], $bccEmails = [])
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

    $data = [
        'personalizations' => $personalizations,
        'from' => [
            'email' => config('services.sendgrid.sender_email'),
            'name' => config('services.sendgrid.sender_name')
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

//quotations calculate rating and assign to user
if (!function_exists('rateQuotation')) {

    function rateQuotation($quotation_id) {

        $quotation = \App\Models\Quotation::find($quotation_id);
        if (!$quotation) {
            throw new \Exception("Quotation not found");
        }

        $rating = 0;

        $quotationmodeoftransport = $quotation->mode_of_transport;
        $cargotype = $quotation->cargo_type;

        $fecha_solicitud = Carbon::parse($quotation->created_at)->startOfDay();

        $unasemanadespues = $fecha_solicitud->copy()->addWeeks(1);
        $dossemanasdespues = $fecha_solicitud->copy()->addWeeks(2);
        $tressemanasdespues = $fecha_solicitud->copy()->addWeeks(3);

        //######## Fecha de envío :::::::::::
        if ($quotation->shipping_date) {
            $fecha_envio = Carbon::parse(explode(' to ', $quotation->shipping_date)[0]);
            if (in_array($quotationmodeoftransport, ['Air', 'Ground'])) {
                if ($fecha_envio->between($fecha_solicitud, $unasemanadespues)) {
                    $rating += 1;
                } else if ($fecha_envio->between($unasemanadespues, $dossemanasdespues)) {
                    $rating += 0.5;
                }
            } else if (in_array($quotationmodeoftransport, ['Container', 'RoRo', 'Breakbulk'])) {
                if ($fecha_envio->between($fecha_solicitud, $tressemanasdespues)) {
                    $rating += 1;
                }
            }
        }


        //######## Correo electrónico :::::::::::
        $email = $quotation->customer_user_id ? \App\Models\User::find($quotation->customer_user_id)->email : \App\Models\GuestUser::find($quotation->guest_user_id)->email;
        $domain = substr(strrchr($email, "@"), 1);

        $personal_domains = [
            'gmail.com',
            'yahoo.com', 'ymail.com', 'yahoo.es', 'yahoo.co.uk', 'yahoo.fr', 'yahoo.de', 'yahoo.it', 'yahoo.ca', 'yahoo.com.mx',
            'outlook.com', 'hotmail.com', 'live.com',
            'aol.com',
            'msn.com',
        ];

        // Verificar si el dominio está en la lista de dominios permitidos o si termina en .edu
        if (!in_array($domain, $personal_domains) && !preg_match('/\.edu(\.[a-z]{2,})?$/', $domain)) {
            $rating += 1;
        }

        //######## Origen/Destino y Ubicación :::::::::::
        $scopeCountries = ['7', '9', '10', '12', '16', '19', '22', '24', '26', '30', '38', '40', '43', '47', '52', '55', '60', '61', '63', '65', '76', '87', '88', '90', '94', '95', '97', '108', '138', '142', '147', '154', '158', '169', '171', '172', '177', '184', '185', '187', '208', '221', '225', '231', '233', '237', '239', '240'];  // Países en el scope
        $specialCountries = ['38', '231']; // Países especiales (Canadá y EE. UU.)

        $location = $quotation->customer_user_id ? \App\Models\User::find($quotation->customer_user_id)->location : \App\Models\GuestUser::find($quotation->guest_user_id)->location;

        $isLocationInSpecialCountries = in_array($location, $specialCountries);

        $isOriginInScope = in_array($quotation->origin_country_id, $scopeCountries);
        $isDestinationInScope = in_array($quotation->destination_country_id, $scopeCountries);

        if ($isLocationInSpecialCountries) {
            if ($isOriginInScope && $isDestinationInScope) {
                $rating += 2; // Tanto origen como destino están en el scope
            } elseif ($isOriginInScope || $isDestinationInScope) {
                $rating += 1; // Origen o destino están en el scope
            } else {
                $rating += 0.5; // Cualquier país fuera del scope
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

        // Cantidades
        // if($quotationmodeoftransport == 'Air'){
        //     if ($quotation->tota_chargeable_weight > 200) {
        //         $rating += 1;
        //     }
        // } else if($cargotype == 'LTL' ){
        //     if ($quotation->total_volum_weight > 1.50) {
        //         $rating += 1;
        //     }
        // } else if($cargotype == 'LCL'){
        //     if ($quotation->total_volum_weight > 1.50) {
        //         $rating += 1;
        //     }
        // } else if($quotationmodeoftransport == 'RoRo'){
        //     if ($quotation->total_volum_weight > 30) {
        //         $rating += 1;
        //     }
        // } else if($quotationmodeoftransport == 'Breakbulk'){
        //     if ($quotation->total_volum_weight > 30) {
        //         $rating += 1;
        //     }
        // }

        // Guarda la calificación en la cotización
        $quotation->rating = $rating;
        $quotation->save();

        //si asignar a usuario si el rating es menor o igual a 4
        if($rating <= 4){
            // Obtener el valor de users_auto_assigned_quotes de la tabla settings
            $users_auto_assigned_quotes = \App\Models\Setting::where('key', 'users_auto_assigned_quotes')->first()->value;
            $userIds = json_decode($users_auto_assigned_quotes);



            // Buscar email en la tabla users el ultimo usuario registrado con el email
            if (!in_array($domain, $personal_domains)){
                // Buscar el último usuario registrado con el mismo dominio en la tabla users
                $customerwithemail = \App\Models\User::where('email', 'like', "%@$domain")->orderBy('id', 'desc')->first();
                if ($customerwithemail) {
                    $quotationwithemail = \App\Models\Quotation::where('customer_user_id', $customerwithemail->id)->orderBy('id', 'desc')->first();
                    if ($quotationwithemail) {
                        $quotationwithemailassigned = \App\Models\User::find($quotationwithemail->assigned_user_id);
                        if ($quotationwithemailassigned) {
                            $quotation->assigned_user_id = $quotationwithemailassigned->id;
                            $quotation->save();
                            return $rating;
                        }
                    }
                }

                // Buscar el último invitado registrado con el mismo dominio en la tabla guest_users
                $guestuserwithemail = \App\Models\GuestUser::where('email', 'like', "%@$domain")->orderBy('id', 'desc')->first();
                if ($guestuserwithemail) {
                    $quotationwithemailguest = \App\Models\Quotation::where('guest_user_id', $guestuserwithemail->id)->orderBy('id', 'desc')->first();
                    if ($quotationwithemailguest) {
                        $quotationwithemailguestassigned = \App\Models\User::find($quotationwithemailguest->assigned_user_id);
                        if ($quotationwithemailguestassigned) {
                            $quotation->assigned_user_id = $quotationwithemailguestassigned->id;
                            $quotation->save();
                            return $rating;
                        }
                    }
                }
            }


            //ver si la cotización cumple con 4 rating
            if($rating == 4){
                $userQuotationFourRatingCounts = [];

                $now = Carbon::now();
                $yesterday = $now->copy()->subDay();


                foreach ($userIds as $userId) {
                    $userQuotationFourRatingCounts[$userId] = \App\Models\Quotation::where('assigned_user_id', $userId)
                    ->where('rating', 4)
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
