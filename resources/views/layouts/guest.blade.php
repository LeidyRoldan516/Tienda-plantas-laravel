<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'El Rincón de las Plantas') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-marca-900 antialiased">
    <div class="flex min-h-screen flex-col items-center bg-lienzo pt-8 sm:justify-center sm:pt-0">
        <div class="mb-2">
            <a href="{{ route('home') }}">
                <x-application-logo class="h-20 w-auto" />
            </a>
        </div>

        <div class="mt-4 w-full overflow-hidden rounded-3xl border border-marca-100 bg-white px-6 py-8 shadow-suave sm:max-w-md">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
