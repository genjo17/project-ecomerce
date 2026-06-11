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
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-6 sm:pt-0 bg-slate-50">
            <div class="text-center border-t-4 border-t-blue-950 pt-6 sm:pt-0">
                <a href="/" class="inline-flex items-center gap-3">
                    <span class="brand-mark h-12 w-12">SB</span>
                    <span class="text-left">
                        <span class="block text-lg font-black tracking-wide text-slate-950">SABISHOP</span>
                        <span class="block text-xs font-medium text-slate-500">Platform belanja online terpercaya</span>
                    </span>
                </a>
            </div>

            <div class="panel mt-6 w-full overflow-hidden px-6 py-6 sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
