@extends('layouts.guest')
@section('title', 'Daftar Akun')

@section('content')
<div class="auth-shell">
    <div class="auth-card animate-in">
        <div class="auth-logo">
            <img src="{{ asset('images/polman.png') }}" alt="Polman">
            <span>POLMAN 375</span>
        </div>

        <h2 class="auth-title" style="font-size: 2rem; font-weight: 800; line-height: 1.1;">Daftar Akun Baru</h2>
        <p class="auth-subtitle" style="font-size: 1.1rem; font-weight: 500; opacity: 0.95; ">Buat akun untuk menyuarakan temuan improvement</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Tipe User --}}
            <div class="form-group">
                <label class="form-label" for="user_type">Tipe Pengguna</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i data-lucide="users"></i></span>
                    <select id="user_type" name="user_type" class="form-select" required onchange="toggleFields()">
                        <option value="">Pilih tipe pengguna</option>
                        <option value="mahasiswa" {{ old('user_type') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ old('user_type') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="umum" {{ old('user_type') === 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>
                @error('user_type') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label" for="full_name">Nama Lengkap</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i data-lucide="user"></i></span>
                    <input type="text" id="full_name" name="full_name" class="form-input" value="{{ old('full_name') }}" required placeholder="Masukkan nama lengkap">
                </div>
                @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i data-lucide="mail"></i></span>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="nama@polman.ac.id">
                </div>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- === Mahasiswa Fields === --}}
            <div id="fields-mahasiswa" style="display:none;">
                <div class="form-group">
                    <label class="form-label" for="nim">NIM</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i data-lucide="hash"></i></span>
                        <input type="text" id="nim" name="nim" class="form-input" value="{{ old('nim') }}" placeholder="Contoh: 221511001">
                    </div>
                    @error('nim') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-2 gap-3">
                    <div class="form-group">
                        <label class="form-label" for="kelas">Kelas</label>
                        <div class="input-wrapper">
                            <span class="input-icon"><i data-lucide="school"></i></span>
                            <input type="text" id="kelas" name="kelas" class="form-input" value="{{ old('kelas') }}" placeholder="Contoh: 2A">
                        </div>
                        @error('kelas') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="tahun_angkatan">Tahun Angkatan</label>
                        <div class="input-wrapper">
                            <span class="input-icon"><i data-lucide="calendar"></i></span>
                            <input type="text" id="tahun_angkatan" name="tahun_angkatan" class="form-input" value="{{ old('tahun_angkatan') }}" placeholder="Contoh: 2022" maxlength="4">
                        </div>
                        @error('tahun_angkatan') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- === Dosen Fields === --}}
            <div id="fields-dosen" style="display:none;">
                <div class="form-group">
                    <label class="form-label" for="nomor_dosen">Nomor Dosen (NIDN/NIP)</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i data-lucide="id-card"></i></span>
                        <input type="text" id="nomor_dosen" name="nomor_dosen" class="form-input" value="{{ old('nomor_dosen') }}" placeholder="Contoh: 0012345678">
                    </div>
                    @error('nomor_dosen') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="jabatan">Jabatan</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i data-lucide="briefcase"></i></span>
                        <input type="text" id="jabatan" name="jabatan" class="form-input" value="{{ old('jabatan') }}" placeholder="Contoh: Lektor / Dosen Tetap">
                    </div>
                    @error('jabatan') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- === Umum Fields === --}}
            <div id="fields-umum" style="display:none;">
                <div class="form-group">
                    <label class="form-label" for="phone">Nomor Telepon</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i data-lucide="phone"></i></span>
                        <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="Contoh: 08123456789">
                    </div>
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i data-lucide="lock"></i></span>
                    <input type="password" id="password" name="password" class="form-input" required placeholder="Minimal 8 karakter">
                </div>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i data-lucide="shield-check"></i></span>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required placeholder="Ulangi password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg mt-2">
                <i data-lucide="user-plus" style="width:18px;height:18px;"></i>
                Daftar Akun
            </button>
        </form>
        <div class="auth-back mt-5">
            <a href="{{ url('/') }}"><i data-lucide="arrow-left"></i> Kembali ke Beranda</a>
        </div>
        <p class="auth-footer">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login di sini</a>
        </p>
    </div>
</div>

@push('styles')
<style>
    .input-wrapper {
        position: relative;
    }
    .input-wrapper .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 1;
        color: rgba(85,136,163,0.65);
        width: 20px;
        height: 20px;
    }
    .form-input {
        padding-left: 3.6rem !important;
    }
    .auth-shell {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem;
        background: #F0F9F4;
        position: relative;
        overflow: hidden;
        transition: background-color 0.4s ease;
    }
    .auth-shell::before,
    .auth-shell::after {
        content: '';
        position: absolute;
        border-radius: 9999px;
        filter: blur(120px);
        opacity: 0.35;
        pointer-events: none;
    }
    .auth-shell::before {
        width: 420px;
        height: 420px;
        top: -100px;
        right: -80px;
        background: rgba(0, 51, 78, 0.1);
    }
    .auth-shell::after {
        width: 520px;
        height: 520px;
        bottom: -140px;
        left: -100px;
        background: rgba(0, 51, 78, 0.08);
    }
    .auth-card {
        width: 100%;
        max-width: 520px;
        position: relative;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid #A0AEC0;
        border-radius: 1.75rem;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(24px);
        padding: 3rem 2.5rem;
        overflow: hidden;
        color: #00334E;
        transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .auth-logo {
        margin-bottom: 2rem;
    }
    .auth-subtitle {
        margin-bottom: 2rem;
        position: relative;
    }
    .auth-subtitle::after {
        content: '';
        position: absolute;
        bottom: -1rem;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, #00334E, rgba(0,51,78,0.35));
        border-radius: 1px;
    }
    .auth-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), rgba(255,255,255,0.6), rgba(255,255,255,0.4), transparent);
        box-shadow: 0 2px 6px rgba(255,255,255,0.25);
    }
    .auth-subtitle {
        margin-bottom: 2rem;
    }
    .auth-title {
        margin-bottom: 0.75rem;
    }
    .auth-back, .auth-footer {
        margin-top: 2rem;
    }
    .auth-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.06), transparent 40%);
        pointer-events: none;
    }
    .auth-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        color: #00334E;
        position: relative;
        z-index: 1;
    }
    .auth-back {
        position: relative;
        z-index: 1;
        margin-bottom: 1rem;
        text-align: center;
    }
    .auth-back a {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.95rem;
        text-decoration: none;
        transition: color 0.2s ease, transform 0.2s ease;
    }
    .auth-back a:hover {
        color: #00334E;
        transform: translateX(-2px);
    }
    .auth-back a i {
        width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .auth-footer {
        color: #475569;
        text-align: center;
        margin-top: 1.4rem;
        font-size: 0.95rem;
    }
    .auth-footer a {
        color: #00334E;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .auth-footer a:hover {
        color: #00334E;
    }
    .auth-logo img {
        width: 56px;
        filter: drop-shadow(0 0 12px rgba(0, 51, 78, 0.15));
    }
    .auth-logo span {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: 1px;
    }
    .animate-in {
        animation: fade-in-up 0.8s ease-out;
    }
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(2rem);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .alert {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border-radius: 1rem;
        border: 1px solid;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.35);
        color: #15803d;
    }
    .alert i {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #38bdf8;
        animation: bounce-icon 0.3s ease-out;
    }
    @keyframes bounce-icon {
        0%, 100% { transform: translateY(-50%) scale(1); }
        50% { transform: translateY(-50%) scale(1.2); }
    }
    .form-input,
    .form-select {
        width: 100%;
        min-height: 3rem;
        padding: 1rem 1rem 1rem 3.6rem;
        color: #00334E;
        background: #FFFFFF !important;
        border: 1px solid #A0AEC0 !important;
        border-radius: 1rem;
        outline: none;
        transition: border-color 0.28s ease, box-shadow 0.28s ease, transform 0.28s ease;
        backdrop-filter: blur(14px);
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    .form-input::placeholder,
    .form-select::placeholder {
        color: rgba(100, 116, 139, 0.65);
    }
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2300334E' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1.1rem center;
        background-size: 1.1rem 1.1rem;
        padding-right: 3.6rem;
    }
    .form-input:focus,
    .form-select:focus {
        border-color: #00334E;
        box-shadow: 0 0 10px rgba(0, 51, 78, 0.15);
        transform: translateY(-1px);
    }
    .btn-primary {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        margin-top: 0.75rem;
        border-radius: 1rem;
        background: #00334E;
        color: #ffffff;
        border: none;
        box-shadow: 0 12px 30px rgba(0, 51, 78, 0.25);
        transition: transform 0.24s ease, box-shadow 0.24s ease, background 0.24s ease;
        z-index: 1;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 40px rgba(0, 51, 78, 0.35);
        background: #001f35;
    }
    .form-error {
        margin-top: 0.55rem;
        color: #f97316;
        font-size: 0.92rem;
    }
    .grid.grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.querySelector('.auth-card');
        if (!card) return;

        if (window.anime && typeof anime === 'function') {
            anime({
                targets: card,
                opacity: [0, 1],
                scale: [0.92, 1],
                duration: 700,
                easing: 'easeOutElastic(1, .85)'
            });
        } else {
            card.style.opacity = '0';
            setTimeout(() => {
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                card.style.opacity = '1';
            }, 50);
        }

        toggleFields();
    });

    function toggleFields() {
        const type = document.getElementById('user_type').value;
        document.getElementById('fields-mahasiswa').style.display = type === 'mahasiswa' ? 'block' : 'none';
        document.getElementById('fields-dosen').style.display = type === 'dosen' ? 'block' : 'none';
        document.getElementById('fields-umum').style.display = type === 'umum' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleFields();
    });
</script>
@endpush
@endsection
