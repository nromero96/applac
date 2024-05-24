<?php
use GuzzleHttp\Client;

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
        // Recupera la cotización usando el ID
        $quotation = \App\Models\Quotation::find($quotation_id);
        if (!$quotation) {
            throw new \Exception("Quotation not found");
        }

        $rating = 0;

        // Fecha de envío
        $fecha_envio = new \Carbon\Carbon(explode(' to ', $quotation->shipping_date)[0]);
        $fecha_solicitud = new \Carbon\Carbon($quotation->created_at);
        $dos_semanas_despues = $fecha_solicitud->copy()->addWeeks(2);
        if ($fecha_envio->between($fecha_solicitud, $dos_semanas_despues)) {
            $rating += 1;
        }

        // Correo electrónico
        $email = $quotation->customer_user_id ? \App\Models\User::find($quotation->customer_user_id)->email : \App\Models\GuestUser::find($quotation->guest_user_id)->email;
        $domain = substr(strrchr($email, "@"), 1);
        $personal_domains = ['gmail.com', 'yahoo.com', 'hotmail.com'];
        if (!in_array($domain, $personal_domains)) {
            $rating += 1;
        }

        // Ubicación origen Canada o United States
        if (in_array($quotation->origin_country_id, ['38', '231'])) {
            $rating += 1;
        }

        // Valor
        if ($quotation->declared_value > 30000) {
            $rating += 1;
        }

        // Cantidades
        switch ($quotation->mode_of_transport) {
            case 'Air':
                if ($quotation->tota_chargeable_weight > 500) {
                    $rating += 1;
                }
            break;
            case 'Ground':
                //cargo_type LTL: Si el volumen en metros cúbicos es superior a 3 
                if($quotation->cargo_type == 'LTL' && $quotation->total_volum_weight > 3){
                    $rating += 1;
                }
            break;
            case 'Container':
                //cargo_type LCL: Si el volumen en metros cúbicos es superior a 3 
                if($quotation->cargo_type == 'LCL' && $quotation->total_volum_weight > 3){
                    $rating += 1;
                    
                }
            break;
            case 'RoRo':
                if ($quotation->total_volum_weight > 3) {
                    $rating += 1;
                    
                }
            break;
            case 'Breakbulk':
                if ($quotation->total_volum_weight > 50) {
                    $rating += 1;
                }
            break;
        }

        // Guarda la calificación en la cotización
        $quotation->rating = $rating;

        //si asignar a usuario si el rating es menor o igual a 4
        if($rating <= 4){

            // Obtener el valor de users_auto_assigned_quotes de la tabla settings
            $users_auto_assigned_quotes = \App\Models\Setting::where('key', 'users_auto_assigned_quotes')->first()->value;
            // Convertir el valor de JSON a array
            $userIds = json_decode($users_auto_assigned_quotes);
            // Inicializar un array para almacenar las cuentas de cotizaciones
            $userQuotationCounts = [];
            // Contar cuántas cotizaciones tiene asignadas cada usuario
            foreach ($userIds as $userId) {
                $userQuotationCounts[$userId] = \App\Models\Quotation::where('assigned_user_id', $userId)->count();
            }
            // Encontrar la cantidad mínima de cotizaciones
            $minCount = min($userQuotationCounts);
            // Filtrar los usuarios que tienen la cantidad mínima de cotizaciones
            $usersWithMinCount = array_filter($userQuotationCounts, function($count) use ($minCount) {
                return $count == $minCount;
            });
            // Seleccionar un usuario al azar entre los que tienen la cantidad mínima de cotizaciones
            $minUserId = array_rand($usersWithMinCount);
            // Asignar la cotización al usuario seleccionado
            $quotation->assigned_user_id = $minUserId;
        }

        $quotation->save();

        return $rating;
    }
}
