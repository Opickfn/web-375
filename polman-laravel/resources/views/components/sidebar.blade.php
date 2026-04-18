<aside class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <div>
            <div class="sidebar-title">Navigasi</div>
        </div>
        <button type="button" class="sidebar-collapse" onclick="toggleSidebarCollapse()" aria-label="Collapse sidebar">
            <i data-lucide="chevron-left" style="width:18px;height:18px;"></i>
        </button>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-label">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" style="width:20px;height:20px;"></i>
            <span>Dashboard</span>
        </a>

        @if(Auth::user()->canCreateReport())
        <a href="{{ route('reports.create') }}" class="sidebar-link {{ request()->routeIs('reports.create') ? 'active' : '' }}">
            <i data-lucide="plus-circle" style="width:20px;height:20px;"></i>
            <span>Buat Laporan</span>
        </a>
        <a href="{{ route('reports.my') }}" class="sidebar-link {{ request()->routeIs('reports.my') ? 'active' : '' }}">
            <i data-lucide="file-text" style="width:20px;height:20px;"></i>
            <span>Laporan Saya</span>
        </a>
        @endif
    </div>

    <div class="sidebar-section">
        <div class="sidebar-label">Poin & Reward</div>
        @if(Auth::user()->canCreateReport())
        <a href="{{ route('points.my') }}" class="sidebar-link {{ request()->routeIs('points.my') ? 'active' : '' }}">
            <i data-lucide="star" style="width:20px;height:20px;"></i>
            <span>Poin Saya</span>
            <span class="sidebar-badge">{{ Auth::user()->totalPoints() }}</span>
        </a>
        @endif
        <a href="{{ route('leaderboard') }}" class="sidebar-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
            <i data-lucide="trophy" style="width:20px;height:20px;"></i>
            <span>Leaderboard</span>
        </a>
    </div>

    @if(Auth::user()->isPjArea() || Auth::user()->isAdmin())
    <div class="sidebar-section">
        <div class="sidebar-label">Manajemen</div>
        <a href="{{ route('reports.review') }}" class="sidebar-link {{ request()->routeIs('reports.review') ? 'active' : '' }}">
            <i data-lucide="clipboard-check" style="width:20px;height:20px;"></i>
            <span>Review Laporan</span>
        </a>
        <a href="{{ route('followups.index') }}" class="sidebar-link {{ request()->routeIs('followups.*') ? 'active' : '' }}">
            <i data-lucide="list-checks" style="width:20px;height:20px;"></i>
            <span>Tindak Lanjut</span>
        </a>
        <a href="{{ route('warnings.index') }}" class="sidebar-link {{ request()->routeIs('warnings.*') ? 'active' : '' }}">
            <i data-lucide="alert-circle" style="width:20px;height:20px;"></i>
            <span>Kelola Peringatan</span>
        </a>
    </div>
    @endif

    @if(Auth::user()->isPjAreaOrAbove())
    <div class="sidebar-section">
        <div class="sidebar-label">Audit</div>
        <a href="{{ route('audits.index') }}" class="sidebar-link {{ request()->routeIs('audits.index') ? 'active' : '' }}">
            <i data-lucide="search-check" style="width:20px;height:20px;"></i>
            <span>Audit PJ Area</span>
        </a>
        <a href="{{ route('logs.history') }}" class="sidebar-link {{ request()->routeIs('logs.history') ? 'active' : '' }}">
            <i data-lucide="clock-history" style="width:20px;height:20px;"></i>
            <span>Riwayat Log</span>
        </a>
    </div>
    @endif

    @if(Auth::user()->isAdmin())
    <div class="sidebar-section">
        <div class="sidebar-label">Administrasi</div>
        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i data-lucide="users" style="width:20px;height:20px;"></i>
            <span>Kelola User</span>
        </a>
        <a href="{{ route('admin.gedung-ruangan') }}" class="sidebar-link {{ request()->routeIs('admin.gedung-ruangan') ? 'active' : '' }}">
            <i data-lucide="building" style="width:20px;height:20px;"></i>
            <span>Gedung & Ruangan</span>
        </a>
        <a href="{{ route('rewards.index') }}" class="sidebar-link {{ request()->routeIs('rewards.*') ? 'active' : '' }}">
            <i data-lucide="gift" style="width:20px;height:20px;"></i>
            <span>Periode Reward</span>
        </a>
    </div>
    @endif
</aside>
