@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card animate-in">
        <div class="auth-logo">
            <img src="{{ asset('images/polman.png') }}" alt="Polman">
            <span>Polman Report</span>
        </div>

        <h2 class="auth-title">Masuk ke Sistem</h2>
        <p class="auth-subtitle">Sistem Pelaporan 5R, 7S & K3</p>

        @if(session('status'))
            <div class="alert alert-success">
                <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="nama@polman.ac.id">
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required placeholder="Masukkan password">
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg mt-2">
                <i data-lucide="log-in" style="width:18px;height:18px;"></i>
                Masuk
            </button>
        </form>

        <p class="text-center text-sm mt-4" style="color: var(--text-secondary);">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar di sini</a>
        </p>
    </div>
</div>
@endsection
