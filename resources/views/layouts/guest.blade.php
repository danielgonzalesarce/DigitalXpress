<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 to-indigo-100">
            <div class="mb-8">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">DigitalXpress</h1>
                        <p class="text-sm text-gray-600">Tu tienda digital de confianza</p>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-xl rounded-2xl border border-gray-100">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} DigitalXpress. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </body>
</html>
