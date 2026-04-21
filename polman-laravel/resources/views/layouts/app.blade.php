<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? '' }}@yield('title', 'Dashboard') — POLMAN 375</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/css/components/navbar.css', 'resources/js/app.js'])
<!-- No extra Livewire JS - @livewireScripts handles it -->
<script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    @livewireStyles
    @stack('styles')
</head>
<body class="pm-bg"> {{-- Gunakan pm-bg untuk background biru gelap --}}
    @include('components.navbar')

<div class="pm-wrapper" style="display: flex; min-height: 100vh;">
        @include('components.sidebar')

        <main class="pm-main">
            {{-- Flash Messages --}}
            @if(session()->has('success'))
            <div class="pm-flash pm-flash-success">
                <i data-lucide="check-circle" style="width:15px;height:15px;"></i>
                {{ session('success') }}
            </div>
            @endif
            @if (session()->has('error'))
            <div class="pm-flash pm-flash-danger">
                <i data-lucide="alert-triangle" style="width:15px;height:15px;"></i>
                {{ session('error') }}
            </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

@livewireScripts

    <script>
function initAppInteractions() {
    // Lucide Icons
    if (window.lucide) lucide.createIcons();

    console.log('[PM-NAV] Initializing app interactions...');
    
    // 1. Profile Dropdown
    const trigger = document.getElementById('pm-user-trigger');
    const dropdown = document.getElementById('pm-user-dropdown');
    if (trigger && dropdown) {
        dropdown.classList.remove('show'); // Initial state
        trigger.onclick = (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        };
        // Close on outside click (window level)
        window.onclick = (e) => {
            if (dropdown && dropdown.classList.contains('show') && !trigger.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        };
    }

    // 2. Sidebar Toggle
    const sidebarToggle = document.getElementById('pm-sidebar-toggle');
    const sidebar = document.querySelector('.pm-sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.onclick = () => {
            sidebar.classList.toggle('open');
        };
    }

    // Desktop Collapse
    const colBtn = document.getElementById('pm-collapse-btn');
    if (colBtn && sidebar) {
        colBtn.style.display = window.innerWidth > 1024 ? 'flex' : 'none';
        colBtn.onclick = () => {
            sidebar.classList.toggle('collapsed');
        };
    }

    // Overlay
    const overlay = document.getElementById('pm-sidebar-overlay');
    if (overlay && sidebar) {
        overlay.onclick = () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        };
    }
}

// Initial load
document.addEventListener('DOMContentLoaded', initAppInteractions);

// Re-run after Livewire navigation
document.addEventListener('livewire:navigated', initAppInteractions);
</script>

    @stack('scripts')
</body>
</html>
