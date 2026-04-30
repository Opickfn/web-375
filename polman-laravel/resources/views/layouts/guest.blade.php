<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Polman Improvement Report')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        :root {
            --navy-primary: #00334E;
            --navy-deep: #00263a;
            --safety-orange: #f59e0b;
        }
        body {
            background-color: var(--navy-deep);
            color: #E8E8E8;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .split-screen {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
    </style>
    @livewireStyles
    @stack('styles')
</head>
<body>
    {{ $slot ?? '' }}
    @yield('content')
    @livewireScripts
    @stack('scripts')
</body>
</html>
