<!DOCTYPE html>
<html lang="hu" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Operations AI') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-hidden">
<canvas id="bg-canvas"></canvas>

<div class="relative z-10 min-h-screen flex flex-col">
    <header class="h-14 flex items-center justify-between px-4 border-b border-white/10 backdrop-blur">
        <div class="font-semibold">⚙ Operations AI</div>

        <nav class="flex gap-4 text-sm">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button>Kilépés</button>
            </form>
        </nav>
    </header>

    <main class="flex-1 overflow-auto">
        {{ $slot }}
    </main>
</div>
</body>
</html>
