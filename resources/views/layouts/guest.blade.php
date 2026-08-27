<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'AutoAlquiler') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Limelight&display=swap" rel="stylesheet" />

        @ddfsnAppearance
        @ddfsnStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[var(--background)] text-[var(--foreground)]">

        @include('layouts.navigation')

        <x-main class="px-0 flex flex-col min-h-[calc(100dvh-5rem)]">
            <div class="flex-1 flex flex-col items-center justify-center py-8 px-4">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
            @include('layouts.footer')
        </x-main>

        @ddfsnScripts
    </body>
</html>
