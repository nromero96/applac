<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    //sendgrid
    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
        'sender_email' => env('SENDGRID_SENDER_EMAIL'),
        'sender_name' => env('SENDGRID_SENDER_NAME'),
        'sender_email_priority' => env('SENDGRID_SENDER_EMAIL_PRIORITY'),
        'sender_name_priority' => env('SENDGRID_SENDER_NAME_PRIORITY'),
    ],

    //copy mail
    'copymail' => [
        'mail_1' => env('MAILCOPY_MAIL_1'),
        'mail_2' => env('MAILCOPY_MAIL_2'),
        'mail_3' => env('MAILCOPY_MAIL_3'),
        'mail_marketing' => env('MAILCOPY_MAIL_MARKETING'),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret' => env('RECAPTCHA_SECRET_KEY'),
    ],

    'quotation_api_token' => env('WEB_QUOTATION_API_TOKEN'),

];
