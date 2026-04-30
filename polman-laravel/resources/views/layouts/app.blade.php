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
    @push('styles')
    <style>
        /* Global CSS Variables K3 7S 5R */
        :root {
            --pm-primary: #00334E;
            --pm-primary-dark: #00263a;
            --pm-accent: #f59e0b;
            --pm-success: #10b981;
            --pm-danger: #ef4444;
            --pm-info: #38bdf8;
            --pm-bg-main: #f8fafc;
            --pm-text-main: #334155;
            --pm-text-muted: #64748b;
        }

        /* Global UI Improvements */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        body.pm-bg {
            background-color: var(--pm-bg-main) !important;
            color: var(--pm-text-main);
        }

        /* Table Design */
        .pm-table { border-collapse: collapse; width: 100%; border-radius: 8px; overflow: hidden; }
        .pm-table th { 
            background-color: var(--pm-primary); 
            color: white; 
            padding: 12px 16px; 
            text-transform: uppercase; 
            font-size: 0.8rem; 
            letter-spacing: 0.05em; 
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }
        .pm-table td { padding: 12px 16px; border-bottom: 1px solid #e2e8f0; }
        .pm-table tbody tr { transition: background-color 0.3s ease; }
        .pm-table tbody tr:nth-child(even) { background-color: #ffffff; }
        .pm-table tbody tr:hover { background-color: #f1f5f9; }

        /* Badges High Contrast (Pill-shaped) */
        .pm-badge { 
            border-radius: 9999px; /* Pill shape */
            padding: 0.25rem 0.75rem; 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            border: none !important; 
            font-weight: bold !important; 
            color: white !important; 
            display: inline-block;
        }
        .pm-badge-danger, .pm-badge-tinggi, .pm-badge-7s { background-color: var(--pm-danger) !important; }
        .pm-badge-warning, .pm-badge-sedang, .pm-badge-pending { background-color: var(--pm-accent) !important; }
        .pm-badge-success, .pm-badge-resolved, .pm-badge-k3, .pm-badge-5r { background-color: var(--pm-success) !important; }
        .pm-badge-info, .pm-badge-rendah, .pm-badge-in_progress { background-color: var(--pm-info) !important; }
        .pm-badge-accent, .pm-badge-approved { background-color: var(--pm-primary) !important; }
        .pm-badge-neutral, .pm-badge-rejected { background-color: #94a3b8 !important; }

        /* Button Hover Effects */
        .pm-btn { transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease; }
        .pm-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .pm-btn-primary { background-color: var(--pm-primary); color: white; }
        .pm-btn-primary:hover { background-color: var(--pm-primary-dark); box-shadow: 0 0 12px rgba(245,158,11,0.5); }
        .pm-btn-success { background-color: var(--pm-success); color: white; }
        .pm-btn-success:hover { background-color: #059669; }
        .pm-btn-danger { background-color: var(--pm-danger); color: white; }
        .pm-btn-danger:hover { background-color: #dc2626; }

        /* Modal Scale-Up Animation */
        .pm-modal-card {
            animation: modalScaleUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes modalScaleUp {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Progress Bar Loading Fill */
        .progress-bar-fill {
            width: 0;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    @endpush
    @stack('styles')
</head>
<body class="pm-bg">
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
