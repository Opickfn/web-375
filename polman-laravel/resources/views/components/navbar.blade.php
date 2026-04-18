@php $newK3Reports = $newK3Reports ?? 0; @endphp
<nav class="navbar">
    <div class="navbar-brand">
        <button class="sidebar-collapse" onclick="toggleSidebar()" aria-label="Toggle sidebar">
            <i data-lucide="menu" style="width:22px;height:22px;"></i>
        </button>
        <div class="navbar-title-group max-w-fit flex-shrink-0">
            <div class="navbar-title">@yield('page-title', 'Ringkasan Dashboard')</div>
            <div class="navbar-subtitle hidden-mobile">Selamat datang kembali, {{ Auth::user()->full_name }}</div>
            <div class="navbar-breadcrumbs hidden-mobile">@yield('breadcrumbs', 'Dashboard')</div>
        </div>
    </div>

    <div class="navbar-content">
        <div class="navbar-actions">
            <button class="btn btn-outline btn-sm{{ $newK3Reports > 0 ? ' navbar-badge' : '' }}" type="button" aria-label="Notifikasi laporan K3">
                <i data-lucide="bell" style="width:20px;height:20px;"></i>
                @if($newK3Reports > 0)
                    <span class="hidden-mobile">{{ $newK3Reports }} baru</span>
                @endif
            </button>
        </div>

        <div class="navbar-user" id="userMenuTrigger" onclick="toggleDropdown()">
            <div class="navbar-user-info hidden-mobile">
                <div class="navbar-user-name">{{ Auth::user()->full_name }}</div>
                <div class="navbar-user-role">{{ Auth::user()->role }}</div>
            </div>
            <div class="navbar-user-avatar">
                {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                <span class="navbar-user-status"></span>
            </div>

            <div class="navbar-dropdown" id="userDropdown">
                <a href="{{ route('dashboard') }}">
                    <i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i>
                    Dashboard
                </a>
                <a href="{{ route('profile') }}">
                    <i data-lucide="user" style="width:16px;height:16px;"></i>
                    Profil Saya
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">
                        <i data-lucide="log-out" style="width:16px;height:16px;"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
