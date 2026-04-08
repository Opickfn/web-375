<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? '' }}@yield('title', 'Dashboard') - Polman Improvement Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @livewireStyles
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Main Content --}}
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success animate-in">
                <i data-lucide="check-circle" style="width:18px;height:18px;flex-shrink:0;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger animate-in">
                <i data-lucide="alert-circle" style="width:18px;height:18px;flex-shrink:0;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @livewireScripts
    <script>
        lucide.createIcons();

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const trigger = document.getElementById('userMenuTrigger');
            if (dropdown && trigger && !trigger.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Re-init icons after Livewire updates
        document.addEventListener('livewire:navigated', () => lucide.createIcons());
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => lucide.createIcons());
        }
    </script>
    @stack('scripts')
</body>
</html>
