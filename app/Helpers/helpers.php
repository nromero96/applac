<?php
use GuzzleHttp\Client;

function sendMailApiLac($toEmail, $subject, $content, $ccEmails = [], $bccEmails = [])
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

    $response = $client->post('https://api.sendgrid.com/v3/mail/send', [
        'headers' => [
            'Authorization' => 'Bearer ' . config('services.sendgrid.api_key'),
            'Content-Type' => 'application/json',
        ],
        'json' => [
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
        ],
    ]);

    if ($response->getStatusCode() == 202) {
        return '1';
    } else {
        return '0';
    }
}