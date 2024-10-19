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

<body class="font-sans antialiased bg-base-200/50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 ">
        <div>
            <a href="/" wire:navigate>
                <x-application-logo class="w-20 h-20 fill-current " />
            </a>
        </div>
        <x-card class="w-full sm:max-w-md mt-6 shadow-xl overflow-hidden sm:rounded-lg border border-base-200">
            {{ $slot }}
        </x-card>
        @if (Route::currentRouteName() == 'login')
            <a class="mt-8 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('register') }}" wire:navigate>
                {{ __('Belum mempunyai akun?') }}
            </a>
        @elseif (Route::currentRouteName() == 'register')
            <a class="mt-8 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}" wire:navigate>
                {{ __('Sudah mempunyai akun?') }}
            </a>
        @endif
    </div>
</body>

</html>
