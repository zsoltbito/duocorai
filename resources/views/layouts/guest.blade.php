<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Operations AI') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-black text-white overflow-hidden">
<canvas id="bg-canvas"></canvas>

<div class="relative z-10 min-h-screen flex items-center justify-center p-4">
    {{ $slot }}
</div>

</body>
</html>
