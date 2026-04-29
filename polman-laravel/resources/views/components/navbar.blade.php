<nav class="navbar">
  <!-- Left Section -->
  <div class="navbar-left">
    <button class="sidebar-toggle" id="pm-sidebar-toggle">
      <i data-lucide="menu" class="w-5 h-5"></i>
    </button>
    <img src="{{ asset('images/polman.png') }}" alt="POLMAN" class="logo">
    <div>
      <div class="navbar-title">POLMAN 375</div>
      <div class="navbar-subtitle" id="pageSubtitle">@yield('page-title', 'Dashboard')</div>
    </div>
  </div>

  <!-- Right Section -->
  <div class="navbar-right">
    <!-- Notification -->
    <button class="notification-btn" title="Notifikasi">
      <i data-lucide="bell" class="w-5 h-5"></i>
      <span class="notification-badge" style="display: {{ $newK3Reports ?? 0 > 0 ? 'block' : 'none' }};"></span>
    </button>

    <!-- Desktop Collapse -->
    <button class="sidebar-toggle collapse-toggle" id="pm-collapse-btn" title="Collapse Sidebar">
      <i data-lucide="panel-left" class="w-5 h-5"></i>
    </button>

    <!-- User Profile -->
    <div class="user-profile" id="pm-user-trigger">
      <div class="user-info">
        <h4>{{ Auth::user()->full_name }}</h4>
        @if (Auth::user()->role === 'reporter')
        <div class="user-role">Kontributor</div>
        @else
        <div class="user-role">{{ Auth::user()->role }}</div>
        @endif
      </div>
      <div class="user-avatar">
        {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
      </div>

      <!-- Dropdown Menu -->
      <div class="dropdown" id="pm-user-dropdown">
        <a wire:navigate href="{{ route('dashboard') }}" class="dropdown-item">

          <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
          Dashboard
        </a>
<a wire:navigate href="{{ route('profile') }}" class="dropdown-item">

          <i data-lucide="user" class="w-4 h-4"></i>
          Profil Saya
        </a>
        <div class="dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}" class="dropdown-item" style="border: none;">
          @csrf
          <button type="submit" class="w-full text-left text-red-400 hover:text-red-300">
            <i data-lucide="log-out" class="w-4 h-4"></i>
            Keluar
          </button>
        </form>
      </div>
    </div>
  </div>
</nav>

