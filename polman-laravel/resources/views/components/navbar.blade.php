<nav class="navbar">
    <div class="navbar-brand">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i data-lucide="menu" style="width:22px;height:22px;"></i>
        </button>
        <img src="{{ asset('images/polman.png') }}" alt="Polman">
        <span class="hidden-mobile">Polman Report</span>
    </div>

    <div class="navbar-content">
        <div class="navbar-user" id="userMenuTrigger" onclick="toggleDropdown()">
            <div class="navbar-user-info hidden-mobile">
                <div class="navbar-user-name">{{ Auth::user()->full_name }}</div>
                <div class="navbar-user-role">{{ Auth::user()->role }}</div>
            </div>
            <div class="navbar-user-avatar">
                {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
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
