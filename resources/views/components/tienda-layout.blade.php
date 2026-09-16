<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'El Rincón de las Plantas' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-sans">
    <div class="flex min-h-screen flex-col">
        @include('partials.navbar')

        @if (session('mensaje') || session('status') || session('success'))
            <div class="contenedor pt-4">
                <div class="rounded-2xl border border-marca-200 bg-marca-50 px-4 py-3 text-sm text-marca-800">
                    {{ session('mensaje') ?? session('status') ?? session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="contenedor pt-4">
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <main class="flex-1">
            {{ $slot }}
        </main>

        @include('partials.footer')
    </div>
</body>

</html>
