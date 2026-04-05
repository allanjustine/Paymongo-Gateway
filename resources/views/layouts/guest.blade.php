<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>
<body class="min-h-screen flex items-center justify-center bg-[#0f0c29] relative overflow-hidden">

    {{-- Animated background blobs --}}
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-600 rounded-full opacity-20 blur-3xl animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-80 h-80 bg-indigo-500 rounded-full opacity-20 blur-3xl animate-pulse delay-1000"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-pink-500 rounded-full opacity-10 blur-3xl"></div>

    <div class="relative z-10 w-full flex items-center justify-center px-4">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
