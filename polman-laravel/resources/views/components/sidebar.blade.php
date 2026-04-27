<aside class="sidebar" id="sidebar">
  <!-- Menu Utama -->
  <div class="sidebar-section">
    <div class="sidebar-label">Menu Utama</div>
<a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

      <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
      <span>Dashboard</span>
    </a>
    
    @if(Auth::user()->canCreateReport())
<a wire:navigate href="{{ route('reports.create') }}" class="sidebar-link {{ request()->routeIs('reports.create') ? 'active' : '' }}">

      <i data-lucide="plus-circle" class="w-5 h-5"></i>
      <span>Buat Laporan</span>
    </a>
<a wire:navigate href="{{ route('reports.my') }}" class="sidebar-link {{ request()->routeIs('reports.my') ? 'active' : '' }}">

      <i data-lucide="file-text" class="w-5 h-5"></i>
      <span>Laporan Saya</span>
    </a>
    @endif
  </div>

  <!-- Poin & Reward -->
  <div class="sidebar-section">
    <div class="sidebar-label">Poin & Reward</div>
    @if(Auth::user()->canCreateReport())
<a wire:navigate href="{{ route('points.my') }}" class="sidebar-link {{ request()->routeIs('points.my') ? 'active' : '' }}">

      <i data-lucide="star" class="w-5 h-5"></i>
      <span>Poin Saya</span>
      <span class="badge-count">{{ Auth::user()->totalPoints() }}</span>
    </a>
    @endif
<a wire:navigate href="{{ route('leaderboard') }}" class="sidebar-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">

      <i data-lucide="trophy" class="w-5 h-5"></i>
      <span>Leaderboard</span>
    </a>
  </div>

  <!-- @if(Auth::user()->isPjArea() || Auth::user()->isAdmin())
  <div class="sidebar-section">
    <div class="sidebar-label">Manajemen</div>
<a wire:navigate href="{{ route('reports.review') }}" class="sidebar-link {{ request()->routeIs('reports.review') ? 'active' : '' }}">

      <i data-lucide="clipboard-check" class="w-5 h-5"></i>
      <span>Review Laporan</span>
    </a>
<a wire:navigate href="{{ route('followups.index') }}" class="sidebar-link {{ request()->routeIs('followups.*') ? 'active' : '' }}">

      <i data-lucide="list-checks" class="w-5 h-5"></i>
      <span>Tindak Lanjut</span>
    </a>
<a wire:navigate href="{{ route('warnings.index') }}" class="sidebar-link {{ request()->routeIs('warnings.*') ? 'active' : '' }}">

      <i data-lucide="alert-triangle" class="w-5 h-5"></i>
      <span>Peringatan</span>
    </a>
  </div>
  @endif -->

  @if(Auth::user()->isPjArea() || Auth::user()->isAdmin() || Auth::user()->isPimpinan())
<div class="sidebar-section">
    <div class="sidebar-label">Operasional & Review</div>
    
    {{-- Menu Review Laporan --}}
    <a wire:navigate href="{{ route('reports.review') }}" class="sidebar-link {{ request()->routeIs('reports.review') ? 'active' : '' }}">
      <i data-lucide="clipboard-check" class="w-5 h-5"></i>
      <span>Review Laporan</span>
    </a>

    {{-- Menu Tindak Lanjut --}}
    <a wire:navigate href="{{ route('followups.index') }}" class="sidebar-link {{ request()->routeIs('followups.index') ? 'active' : '' }}">
      <i data-lucide="check-square" class="w-5 h-5"></i>
      <span>Tindak Lanjut</span>
    </a>

    {{-- Menu Peringatan (Sekarang Pimpinan bisa akses) --}}
    <a wire:navigate href="{{ route('warnings.index') }}" class="sidebar-link {{ request()->routeIs('warnings.*') ? 'active' : '' }}">
      <i data-lucide="alert-triangle" class="w-5 h-5"></i>
      <span>Peringatan</span>
    </a>
</div>
@endif

  @if(Auth::user()->isPjAreaOrAbove())
  <!-- Audit & Log -->
  <div class="sidebar-section">
    <div class="sidebar-label">Audit & Log</div>
<a wire:navigate href="{{ route('audits.index') }}" class="sidebar-link {{ request()->routeIs('audits.*') ? 'active' : '' }}">

      <i data-lucide="search-check" class="w-5 h-5"></i>
      <span>Audit PJ Area</span>
    </a>
<a wire:navigate href="{{ route('logs.history') }}" class="sidebar-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">

      <i data-lucide="history" class="w-5 h-5"></i>
      <span>Riwayat Log</span>
    </a>
  </div>
  @endif

  @if(Auth::user()->isAdmin())
  <!-- Administrasi -->
  <div class="sidebar-section">
    <div class="sidebar-label">Administrasi</div>
<a wire:navigate href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">

      <i data-lucide="users" class="w-5 h-5"></i>
      <span>Kelola User</span>
    </a>
<a wire:navigate href="{{ route('admin.gedung-ruangan') }}" class="sidebar-link {{ request()->routeIs('admin.gedung-ruangan') ? 'active' : '' }}">

      <i data-lucide="building" class="w-5 h-5"></i>
      <span>Gedung & Lokasi</span>
    </a>
<a wire:navigate href="{{ route('rewards.index') }}" class="sidebar-link {{ request()->routeIs('rewards.*') ? 'active' : '' }}">

      <i data-lucide="gift" class="w-5 h-5"></i>
      <span>Periode Reward</span>
    </a>
  </div>
  @endif

  <!-- Profile -->
  <div class="sidebar-section" style="margin-top: auto;">
<a wire:navigate href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile') ? 'active' : '' }}">

      <i data-lucide="settings" class="w-5 h-5"></i>
      <span>Profil & Pengaturan</span>
    </a>
  </div>
</aside>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

