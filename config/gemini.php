<?php

/**
 * Autor: Simon Martinez Gomez
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Gemini API Key
    |--------------------------------------------------------------------------
    |
    | Credenciales para autenticar las solicitudes a Gemini.
    | Definir únicamente en el archivo .env local (nunca en el repositorio).
    */

    'api_key' => env('GEMINI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Gemini Base URL
    |--------------------------------------------------------------------------
    |
    | URL base de la API. Si se deja vacío, la librería usa el valor por defecto.
    */

    'base_url' => rtrim((string) env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'), '/').'/',

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Tiempo máximo de espera, en segundos, para cada solicitud a Gemini.
    */

    'request_timeout' => env('GEMINI_REQUEST_TIMEOUT', 30),
];
