@extends('layouts.guest')
@section('title', 'Daftar Akun')

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
                    <div class="edu-icon"><i data-lucide="shield-check"></i></div>
                    <h2 class="edu-title">K3 (Keselamatan & Kesehatan Kerja)</h2>
                    <p class="edu-text">Prioritas utama dalam setiap jengkal workshop. Laporkan potensi bahaya sebelum menjadi insiden.</p>
                </div>
                <div class="edu-slide" data-index="1">
                    <div class="edu-icon"><i data-lucide="award"></i></div>
                    <h2 class="edu-title">7S (Seven Waste)</h2>
                    <p class="edu-text">7 pemborosan yang sering terjadi di lingkungan kerja. 
                       <br>Transport, Inventory, Motion, Waiting, Overproduction, <br>Over-processing, Defects
                </div>
                <div class="edu-slide" data-index="2">
                    <div class="edu-icon"><i data-lucide="layout-grid"></i></div>
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
        <div class="auth-card-wrapper animate-in" style="max-width: 550px;">
            <div class="mobile-logo lg:hidden">
                <img src="{{ asset('images/polman.png') }}" alt="Polman Logo">
                <span>POLMAN 375</span>
            </div>

            <div class="auth-form-card">
                <div class="form-header">
                    <h2 class="form-title">Daftar Akun</h2>
                    <p class="form-subtitle">Buat akun untuk menyuarakan temuan improvement</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Tipe User --}}
                    <div class="form-field">
                        <label for="user_type" class="field-label">Tipe Pengguna</label>
                        <div class="input-container">
                            <i data-lucide="users" class="input-icon"></i>
                            <select id="user_type" name="user_type" class="modern-input appearance-none text-slate-900 bg-white" required onchange="toggleFields()">
    <option value="" disabled selected>Pilih tipe pengguna</option>
    <option value="mahasiswa" {{ old('user_type') === 'mahasiswa' ? 'selected' : '' }} class="text-slate-900">Mahasiswa</option>
    <option value="dosen" {{ old('user_type') === 'dosen' ? 'selected' : '' }} class="text-slate-900">Dosen</option>
    <option value="umum" {{ old('user_type') === 'umum' ? 'selected' : '' }} class="text-slate-900">Umum</option>
</select>
                        </div>
                        @error('user_type') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="form-field">
                        <label for="full_name" class="field-label">Nama Lengkap</label>
                        <div class="input-container">
                            <i data-lucide="user" class="input-icon"></i>
                            <input type="text" id="full_name" name="full_name" class="modern-input" value="{{ old('full_name') }}" required placeholder="Masukkan nama lengkap">
                        </div>
                        @error('full_name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-field">
                        <label for="email" class="field-label">Alamat Email</label>
                        <div class="input-container">
                            <i data-lucide="mail" class="input-icon"></i>
                            <input type="email" id="email" name="email" class="modern-input" value="{{ old('email') }}" required placeholder="nama@polman.ac.id">
                        </div>
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- === Mahasiswa Fields === --}}
                    <div id="fields-mahasiswa" style="display:none;" class="space-y-4">
                        <div class="form-field">
                            <label for="nim" class="field-label">NIM</label>
                            <div class="input-container">
                                <i data-lucide="hash" class="input-icon"></i>
                                <input type="text" id="nim" name="nim" class="modern-input" value="{{ old('nim') }}" placeholder="Contoh: 221511001">
                            </div>
                            @error('nim') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-field">
                                <label for="kelas" class="field-label">Kelas</label>
                                <div class="input-container">
                                    <i data-lucide="school" class="input-icon"></i>
                                    <input type="text" id="kelas" name="kelas" class="modern-input" value="{{ old('kelas') }}" placeholder="2A">
                                </div>
                                @error('kelas') <p class="field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-field">
                                <label for="tahun_angkatan" class="field-label">Angkatan</label>
                                <div class="input-container">
                                    <i data-lucide="calendar" class="input-icon"></i>
                                    <input type="text" id="tahun_angkatan" name="tahun_angkatan" class="modern-input" value="{{ old('tahun_angkatan') }}" placeholder="2022" maxlength="4">
                                </div>
                                @error('tahun_angkatan') <p class="field-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- === Dosen Fields === --}}
                    <div id="fields-dosen" style="display:none;" class="space-y-4">
                        <div class="form-field">
                            <label for="nomor_dosen" class="field-label">Nomor Dosen (NIDN/NIP)</label>
                            <div class="input-container">
                                <i data-lucide="id-card" class="input-icon"></i>
                                <input type="text" id="nomor_dosen" name="nomor_dosen" class="modern-input" value="{{ old('nomor_dosen') }}" placeholder="Masukkan NIDN/NIP">
                            </div>
                            @error('nomor_dosen') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-field">
                            <label for="jabatan" class="field-label">Jabatan</label>
                            <div class="input-container">
                                <i data-lucide="briefcase" class="input-icon"></i>
                                <input type="text" id="jabatan" name="jabatan" class="modern-input" value="{{ old('jabatan') }}" placeholder="Contoh: Lektor">
                            </div>
                            @error('jabatan') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- === Umum Fields === --}}
                    <div id="fields-umum" style="display:none;" class="space-y-4">
                        <div class="form-field">
                            <label for="phone" class="field-label">Nomor Telepon</label>
                            <div class="input-container">
                                <i data-lucide="phone" class="input-icon"></i>
                                <input type="text" id="phone" name="phone" class="modern-input" value="{{ old('phone') }}" placeholder="08123456789">
                            </div>
                            @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-field">
                            <label for="password" class="field-label">Password</label>
                            <div class="input-container">
                                <i data-lucide="lock" class="input-icon"></i>
                                <input type="password" id="password" name="password" class="modern-input" required placeholder="Min. 8 kar">
                            </div>
                            @error('password') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-field">
                            <label for="password_confirmation" class="field-label">Konfirmasi</label>
                            <div class="input-container">
                                <i data-lucide="shield-check" class="input-icon"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="modern-input" required placeholder="Ulangi">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth-primary">
                        <span>Daftar Sekarang</span>
                        <i data-lucide="user-plus"></i>
                    </button>
                </form>

                <div class="form-footer">
                    <p>Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
                    <a href="{{ url('/') }}" class="back-home"><i data-lucide="home"></i> Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Split Screen Layout */
    .auth-visual {
        flex: 1.2;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem;
        background: #001524;
    }
    @media (max-width: 1024px) {
        .auth-visual {
            display: none !important;
        }
    }
    .visual-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: scale(1.05);
        animation: slowZoom 20s infinite alternate;
    }
    @keyframes slowZoom {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }
    .visual-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0, 51, 78, 0.95) 0%, rgba(0, 21, 36, 0.8) 100%);
    }
    .visual-content {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 600px;
    }
    .visual-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 5rem;
    }
    .visual-logo {
        width: 80px;
        filter: drop-shadow(0 0 20px rgba(245, 158, 11, 0.3));
    }
    .visual-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: white;
        margin: 0;
        line-height: 1.1;
    }
    .visual-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        margin: 0.5rem 0 0 0;
    }

    /* Edu Slider */
    .edu-slider {
        position: relative;
        min-height: 200px;
    }
    .edu-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    .edu-slide.active {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }
    .edu-icon {
        width: 48px;
        height: 48px;
        background: rgba(245, 158, 11, 0.2);
        border: 1px solid rgba(245, 158, 11, 0.4);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f59e0b;
        margin-bottom: 1.5rem;
    }
    .edu-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
    }
    .edu-text {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.6;
    }
    .slider-dots {
        display: flex;
        gap: 0.5rem;
        margin-top: 3rem;
    }
    .dot {
        width: 24px;
        height: 4px;
        background: rgba(255,255,255,0.2);
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    .dot.active {
        background: #f59e0b;
        width: 40px;
    }

    /* Form Side */
    .auth-form-side {
        flex: 1;
        background: #001524;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    .auth-card-wrapper {
        width: 100%;
    }
    .auth-form-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 2.5rem;
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .form-header {
        margin-bottom: 2rem;
    }
    .form-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
    }
    .form-subtitle {
        color: rgba(255,255,255,0.5);
        font-size: 0.9rem;
    }

    /* Modern Inputs */
    .form-field {
        margin-bottom: 1rem;
    }
    .field-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(255,255,255,0.6);
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .input-container {
        position: relative;
    }
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: rgba(255,255,255,0.3);
        transition: color 0.3s ease;
    }
    .modern-input {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem 0.75rem 3.25rem;
        color: white;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    .modern-input:focus {
        background: rgba(255,255,255,0.08);
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        outline: none;
    }
    .modern-input:focus + .input-icon {
        color: #f59e0b;
    }
    .field-error {
        color: #f87171;
        font-size: 0.7rem;
        margin-top: 0.3rem;
    }

    /* Select specific styling */
    select.modern-input {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff66' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.25rem;
    }

    /* Auth Button */
    .btn-auth-primary {
        width: 100%;
        background: #f59e0b;
        color: #001524;
        font-weight: 700;
        padding: 0.875rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        margin-top: 1rem;
    }
    .btn-auth-primary:hover {
        background: #fbbf24;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4);
    }

    /* Footer */
    .form-footer {
        margin-top: 2rem;
        text-align: center;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.4);
    }
    .form-footer a {
        color: #f59e0b;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .back-home {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
        color: rgba(255,255,255,0.3) !important;
    }

    /* Mobile adjustments */
    .mobile-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .mobile-logo img {
        width: 40px;
    }
    .mobile-logo span {
        font-size: 1.25rem;
        font-weight: 800;
        color: white;
    }

    @media (max-width: 1024px) {
        .auth-form-side {
            padding: 1rem;
        }
        .auth-form-card {
            padding: 1.75rem;
            border-radius: 1.25rem;
        }
    }

    .animate-in {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleFields() {
        const type = document.getElementById('user_type').value;
        const mahasistwa = document.getElementById('fields-mahasiswa');
        const dosen = document.getElementById('fields-dosen');
        const umum = document.getElementById('fields-umum');
        
        // Hide all with transition if possible
        [mahasistwa, dosen, umum].forEach(el => {
            el.style.display = 'none';
        });

        if (type === 'mahasiswa') mahasistwa.style.display = 'block';
        if (type === 'dosen') dosen.style.display = 'block';
        if (type === 'umum') umum.style.display = 'block';
        
        // Re-init icons for new fields
        if (window.lucide) lucide.createIcons();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Slider Logic
        const slides = document.querySelectorAll('.edu-slide');
        const dots = document.querySelectorAll('.dot');
        let currentSlide = 0;

        function nextSlide() {
            if (slides.length === 0) return;
            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');
            
            currentSlide = (currentSlide + 1) % slides.length;
            
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        if (slides.length > 0) setInterval(nextSlide, 5000);

        // Initialize Lucide icons
        if (window.lucide) {
            lucide.createIcons();
        }

        // Initial toggle
        toggleFields();
    });
</script>
@endpush
@endsection
