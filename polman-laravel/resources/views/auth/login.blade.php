@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="split-screen">
    {{-- Left Side: Visual & Education --}}
    <div class="auth-visual">
        <div class="visual-bg" style="background-image: url('{{ asset('images/auth-bg.png') }}');"></div>
        <div class="visual-overlay"></div>
        
        <div class="visual-content">
            <div class="visual-header">
                <img src="{{ asset('images/polman.png') }}" alt="Polman Logo" class="visual-logo">
                <div>
                    <h1 class="visual-title">Sistem Improvement POLMAN 375</h1>
                    <p class="visual-subtitle">Membangun Budaya Industri Unggul</p>
                </div>
            </div>

            <div class="edu-slider">
                <div class="edu-slide active" data-index="0">
                    <div class="edu-icon flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-white/10 mb-4"><i data-lucide="shield-check"></i></div>
                    <h2 class="edu-title">K3 (Keselamatan & Kesehatan Kerja)</h2>
                    <p class="edu-text">Prioritas utama dalam setiap jengkal workshop. Laporkan potensi bahaya sebelum menjadi insiden.</p>
                </div>
                <div class="edu-slide" data-index="1">
                    <div class="edu-icon flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-white/10 mb-4"><i data-lucide="award"></i></div>
                    <h2 class="edu-title">7S (Seven Waste)</h2>
                    <p class="edu-text">7 pemborosan yang sering terjadi di lingkungan kerja. 
                       <br>Transport, Inventory, Motion, Waiting, Overproduction, <br> Over-processing, Defects
                </div>
                <div class="edu-slide" data-index="2">
                    <div class="edu-icon  flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-white/10 mb-4"><i data-lucide="layout-grid"></i></div>
                    <h2 class="edu-title">5R Metodologi</h2>
                    <p class="edu-text">Ringkas, Rapi, Resik, Rawat, Rajin. Ciptakan lingkungan kerja yang efisien dan produktif.</p>
                </div>
                
                <div class="slider-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side: Auth Form --}}
    <div class="auth-form-side">
        <div class="auth-card-wrapper animate-in">
            <div class="mobile-logo lg:hidden">
                <img src="{{ asset('images/polman.png') }}" alt="Polman Logo">
                <span>POLMAN 375</span>
            </div>

            <div class="auth-form-card">
                <div class="form-header">
                    <h2 class="form-title">Selamat Datang</h2>
                    <p class="form-subtitle">Silakan masuk untuk melanjutkan kontribusi Anda</p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success">
                        <i data-lucide="check-circle"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="form-field">
                        <label for="email" class="field-label">Alamat Email</label>
                        <div class="input-container">
                            <i data-lucide="mail" class="input-icon"></i>
                            <input type="email" id="email" name="email" class="modern-input" value="{{ old('email') }}" required autofocus placeholder="nama@polman.ac.id">
                        </div>
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-field">
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="field-label">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-orange-400 hover:text-orange-300 transition-colors">Lupa Password?</a>
                            @endif
                        </div>
                        <div class="input-container">
                            <i data-lucide="lock" class="input-icon"></i>
                            <input type="password" id="password" name="password" class="modern-input" required placeholder="Masukkan password Anda">
                        </div>
                        @error('password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember_me" name="remember" class="rounded border-gray-700 bg-gray-800 text-orange-500 focus:ring-orange-500">
                        <label for="remember_me" class="ml-2 text-sm text-gray-400">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn-auth-primary">
                        <span>Masuk ke Sistem</span>
                        <i data-lucide="arrow-right"></i>
                    </button>
                </form>

                <div class="form-footer">
                    <p>Belum memiliki akun? <a href="{{ route('register') }}">Daftar Sekarang</a></p>
                    <a href="{{ url('/') }}" class="back-home"><i data-lucide="home"></i> Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>

@include('auth.partials.style-auth')

