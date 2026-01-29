<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DistroZone') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <x-navbar />

        <!-- Main Content with Top Padding -->
        <main class="pt-24 pb-12">
            <!-- Store Closed Warning Banner -->
            <x-store-closed-banner />

            {{ $slot }}
        </main>

        <x-footer />
    </div>

    <!-- Flash Messages -->
    <!-- Global Notifications -->
    <x-toast-notification />
    <x-confirm-modal />

    <!-- Chat Widget (Everywhere except if explicitly hidden) -->
    @unless (request()->routeIs('settings*'))
        <x-chat-widget />
    @endunless
</body>

</html>
