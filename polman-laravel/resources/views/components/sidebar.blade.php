<aside class="sidebar" id="sidebar">
    {{-- Menu Utama --}}
    <div class="sidebar-section">
        <div class="sidebar-label">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" style="width:20px;height:20px;"></i>
            Dashboard
        </a>

        @if(Auth::user()->canCreateReport())
        <a href="{{ route('reports.create') }}" class="sidebar-link {{ request()->routeIs('reports.create') ? 'active' : '' }}">
            <i data-lucide="plus-circle" style="width:20px;height:20px;"></i>
            Buat Laporan
        </a>
        <a href="{{ route('reports.my') }}" class="sidebar-link {{ request()->routeIs('reports.my') ? 'active' : '' }}">
            <i data-lucide="file-text" style="width:20px;height:20px;"></i>
            Laporan Saya
        </a>
        @endif
    </div>

    {{-- Points --}}
    <div class="sidebar-section">
        <div class="sidebar-label">Poin & Reward</div>
        @if(Auth::user()->canCreateReport())
        <a href="{{ route('points.my') }}" class="sidebar-link {{ request()->routeIs('points.my') ? 'active' : '' }}">
            <i data-lucide="star" style="width:20px;height:20px;"></i>
            Poin Saya
            <span class="sidebar-badge">{{ Auth::user()->totalPoints() }}</span>
        </a>
        @endif
        <a href="{{ route('leaderboard') }}" class="sidebar-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
            <i data-lucide="trophy" style="width:20px;height:20px;"></i>
            Leaderboard
        </a>
    </div>

    {{-- Manager Menu --}}
    @if(Auth::user()->isManagerOrAbove())
    <div class="sidebar-section">
        <div class="sidebar-label">Manajemen</div>
        <a href="{{ route('reports.review') }}" class="sidebar-link {{ request()->routeIs('reports.review') ? 'active' : '' }}">
            <i data-lucide="clipboard-check" style="width:20px;height:20px;"></i>
            Review Laporan
        </a>
        <a href="{{ route('followups.index') }}" class="sidebar-link {{ request()->routeIs('followups.*') ? 'active' : '' }}">
            <i data-lucide="list-checks" style="width:20px;height:20px;"></i>
            Tindak Lanjut
        </a>
    </div>
    @endif

    {{-- Admin Menu --}}
    @if(Auth::user()->isAdmin())
    <div class="sidebar-section">
        <div class="sidebar-label">Administrasi</div>
        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i data-lucide="users" style="width:20px;height:20px;"></i>
            Kelola User
        </a>
        <a href="{{ route('admin.gedung-ruangan') }}" class="sidebar-link {{ request()->routeIs('admin.gedung-ruangan') ? 'active' : '' }}">
            <i data-lucide="building" style="width:20px;height:20px;"></i>
            Gedung & Ruangan
        </a>
        <a href="{{ route('rewards.index') }}" class="sidebar-link {{ request()->routeIs('rewards.*') ? 'active' : '' }}">
            <i data-lucide="gift" style="width:20px;height:20px;"></i>
            Periode Reward
        </a>
    </div>
    @endif
</aside>
