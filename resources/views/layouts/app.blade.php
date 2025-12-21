<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Operations AI') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white overflow-hidden">
<canvas id="bg-canvas"></canvas>

<div class="relative z-10 min-h-screen flex flex-col">
    <header class="h-14 flex items-center justify-between px-4 border-b border-white/10 backdrop-blur">
        <div class="font-semibold tracking-wide">⚙ Operations AI</div>

        <nav class="flex items-center gap-4 text-sm opacity-90">
            <a class="hover:opacity-100 opacity-80" href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="hover:opacity-100 opacity-80">Kilépés</button>
            </form>
        </nav>
    </header>

    <main class="flex-1 overflow-auto">
        {{ $slot }}
    </main>
</div>
</body>
</html>
