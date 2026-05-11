@extends('layouts.guest')
@section('title', 'Lupa Password')

@section('content')
<div class="split-screen">
    {{-- Left Side: Visual & Education (Tetap Konsisten dengan Login) --}}
    <div class="auth-visual">
        <div class="visual-bg" style="background-image: url('{{ asset('images/auth-bg.png') }}');"></div>
        <div class="visual-overlay"></div>
        
        <div class="visual-content">
            <div class="visual-header">
                <img src="{{ asset('images/polman.png') }}" alt="Polman Logo" class="visual-logo">
                <div>
                    <h1 class="visual-title">Sistem Improvement POLMAN 375</h1>
                    <p class="visual-subtitle">Pemulihan Akses Akun</p>
                </div>
            </div>

            <div class="edu-slider">
                <div class="edu-slide active">
                    <div class="edu-icon"><i data-lucide="key-round"></i></div>
                    <h2 class="edu-title">Keamanan Akun</h2>
                    <p class="edu-text">Jangan khawatir, masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side: Forgot Password Form --}}
    <div class="auth-form-side">
        <div class="auth-card-wrapper animate-in">
            <div class="auth-form-card">
                <div class="form-header">
                    <h2 class="form-title">Lupa Password?</h2>
                    <p class="form-subtitle">Sebutkan email akun Anda untuk menerima tautan pemulihan.</p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success bg-green-500/10 border border-green-500/50 text-green-500 p-4 rounded-lg mb-6 flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span class="text-sm">{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div class="form-field">
                        <label for="email" class="field-label">Alamat Email</label>
                        <div class="input-container">
                            <i data-lucide="mail" class="input-icon"></i>
                            <input type="email" id="email" name="email" class="modern-input" value="{{ old('email') }}" required autofocus placeholder="nama@polman.ac.id">
                        </div>
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-auth-primary">
                        <span>Kirim Link Pemulihan</span>
                        <i data-lucide="send"></i>
                    </button>
                </form>

                <div class="form-footer">
                    <a href="{{ route('login') }}" class="back-home"><i data-lucide="arrow-left"></i> Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

@include('auth.partials.style-auth') 
@endsection