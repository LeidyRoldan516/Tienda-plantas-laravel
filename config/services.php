<?php

/**
 * Autor: Simon Martinez Gomez
 */

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

    'monoverse' => [
        'base_url' => env('MONOVERSE_BASE_URL', 'https://monoverse-414457182299.us-central1.run.app'),
    ],

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    /*
    | Credenciales de Gemini para recomendaciones personalizadas.
    | Definir únicamente en el archivo .env local (nunca en el repositorio).
    */
    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'base_url' => rtrim((string) env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'), '/').'/',
        'model' => env('GEMINI_MODEL', 'gemini-3.6-flash'),
    ],

    /*
    | Implementación del recomendador: "gemini" (servicio externo) o "local" (respaldo).
    | Si se elige "gemini" sin API key, se usa automáticamente el recomendador local.
    */
    'recomendaciones' => [
        'driver' => env('RECOMENDACIONES_DRIVER', 'gemini'),
    ],

];
