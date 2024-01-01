<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 scroll-smooth dark:bg-neutral-700">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon"/>

        <!-- Fathom - beautiful, simple website analytics -->
        @env('production')
        <script src="https://cdn.usefathom.com/script.js" data-site="KVCJTXET" defer></script>
        @endenv
        <!-- / Fathom -->

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="antialiased h-full relative">
        @inertia
    </body>
</html>
