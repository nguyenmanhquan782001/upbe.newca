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
    'facebook' => [
        'client_id' => '1424576097792929',
        'client_secret' => '8355b6df58dcd579bde378df068c8aef',
        'redirect' => 'https://portal.newca.vn/auth/facebook/callback',
    ],
    'google' => [
        'client_id' => '818031211174-4rc6rmnh41jb6kil6527n9hbvu1p1l3k.apps.googleusercontent.com',
        'client_secret' => 'NBsFwHk5e7FjX71dm9QWjXs3',
        'redirect' => 'https://portal.newca.vn/auth/google/callback',
    ],
    'affiliate'=>[
        'endpoint' => env('AFFILIATE_ENDPOINT', 'https://htdn.net/api/'),
    ],
    'keycloak' => [
        'client_id' => env('KEYCLOAK_CLIENTID'),
        'client_secret' => env('KEYCLOAK_CLIENTSECRET'),
        'redirect' => env('KEYCLOAK_REDIRECT_URI'),
        'base_url' => env('KEYCLOAK_AUTHSERVERURL'),   // Specify your keycloak server URL here
        'realms' => env('KEYCLOAK_REALM')         // Specify your keycloak realm
    ],
];
