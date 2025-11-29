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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /**
     * Configuración de Google OAuth 2.0
     * 
     * Credenciales para autenticación con Google usando Laravel Socialite.
     * Estas credenciales se obtienen desde Google Cloud Console.
     * 
     * Variables de entorno requeridas en .env:
     * - GOOGLE_CLIENT_ID: ID del cliente OAuth 2.0
     * - GOOGLE_CLIENT_SECRET: Secreto del cliente OAuth 2.0
     * - GOOGLE_REDIRECT_URI: URI de redirección autorizado (ej: http://127.0.0.1:8081/auth/google/callback)
     */
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),           // ID del cliente OAuth
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),   // Secreto del cliente OAuth
        'redirect' => env('GOOGLE_REDIRECT_URI'),         // URI de callback después de autenticación
    ],

];
