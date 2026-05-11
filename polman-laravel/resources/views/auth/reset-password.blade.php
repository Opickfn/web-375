@extends('layouts.guest')
@section('title', 'Reset Password')

@section('content')
<div class="split-screen">
    <div class="auth-visual">
        <div class="visual-bg" style="background-image: url('{{ asset('images/auth-bg.png') }}');"></div>
        <div class="visual-overlay"></div>
        <div class="visual-content">
            <div class="visual-header">
                <img src="{{ asset('images/polman.png') }}" alt="Polman Logo" class="visual-logo">
                <h1 class="visual-title">Atur Ulang Password</h1>
            </div>
        </div>
    </div>

    <div class="auth-form-side">
        <div class="auth-card-wrapper animate-in">
            <div class="auth-form-card">
                <div class="form-header">
                    <h2 class="form-title">Password Baru</h2>
                    <p class="form-subtitle">Silakan buat password baru yang kuat untuk akun Anda.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    {{-- Token penting untuk keamanan --}}
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-field">
                        <label for="email" class="field-label">Alamat Email</label>
                        <div class="input-container">
                            <i data-lucide="mail" class="input-icon"></i>
                            <input type="email" id="email" name="email" class="modern-input" value="{{ old('email', $request->email) }}" required readonly>
                        </div>
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-field">
                        <label for="password" class="field-label">Password Baru</label>
                        <div class="input-container">
                            <i data-lucide="lock" class="input-icon"></i>
                            <input type="password" id="password" name="password" class="modern-input" required placeholder="Minimal 8 karakter">
                        </div>
                        @error('password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-field">
                        <label for="password_confirmation" class="field-label">Konfirmasi Password</label>
                        <div class="input-container">
                            <i data-lucide="shield-check" class="input-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="modern-input" required placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <button type="submit" class="btn-auth-primary">
                        <span>Perbarui Password</span>
                        <i data-lucide="check-circle"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@include('auth.partials.style-auth')
@endsection