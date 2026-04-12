@extends('layouts.guest')
@section('title', 'Daftar Akun')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card animate-in" style="max-width:520px;">
        <div class="auth-logo">
            <img src="{{ asset('images/polman.png') }}" alt="Polman">
            <span>Polman Report</span>
        </div>

        <h2 class="auth-title">Daftar Akun Baru</h2>
        <p class="auth-subtitle">Buat akun untuk melaporkan temuan improvement</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Tipe User --}}
            <div class="form-group">
                <label class="form-label" for="user_type">Tipe Pengguna</label>
                <select id="user_type" name="user_type" class="form-select" required onchange="toggleFields()">
                    <option value="">Pilih tipe pengguna</option>
                    <option value="mahasiswa" {{ old('user_type') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ old('user_type') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="umum" {{ old('user_type') === 'umum' ? 'selected' : '' }}>Umum</option>
                </select>
                @error('user_type') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label" for="full_name">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" class="form-input" value="{{ old('full_name') }}" required placeholder="Masukkan nama lengkap">
                @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="nama@polman.ac.id">
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- === Mahasiswa Fields === --}}
            <div id="fields-mahasiswa" style="display:none;">
                <div class="form-group">
                    <label class="form-label" for="nim">NIM</label>
                    <input type="text" id="nim" name="nim" class="form-input" value="{{ old('nim') }}" placeholder="Contoh: 221511001">
                    @error('nim') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-2 gap-3">
                    <div class="form-group">
                        <label class="form-label" for="kelas">Kelas</label>
                        <input type="text" id="kelas" name="kelas" class="form-input" value="{{ old('kelas') }}" placeholder="Contoh: 2A">
                        @error('kelas') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="tahun_angkatan">Tahun Angkatan</label>
                        <input type="text" id="tahun_angkatan" name="tahun_angkatan" class="form-input" value="{{ old('tahun_angkatan') }}" placeholder="Contoh: 2022" maxlength="4">
                        @error('tahun_angkatan') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- === Dosen Fields === --}}
            <div id="fields-dosen" style="display:none;">
                <div class="form-group">
                    <label class="form-label" for="nomor_dosen">Nomor Dosen (NIDN/NIP)</label>
                    <input type="text" id="nomor_dosen" name="nomor_dosen" class="form-input" value="{{ old('nomor_dosen') }}" placeholder="Contoh: 0012345678">
                    @error('nomor_dosen') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" class="form-input" value="{{ old('jabatan') }}" placeholder="Contoh: Lektor / Dosen Tetap">
                    @error('jabatan') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- === Umum Fields === --}}
            <div id="fields-umum" style="display:none;">
                <div class="form-group">
                    <label class="form-label" for="phone">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="Contoh: 08123456789">
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required placeholder="Minimal 8 karakter">
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required placeholder="Ulangi password">
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg mt-2">
                <i data-lucide="user-plus" style="width:18px;height:18px;"></i>
                Daftar Akun
            </button>
        </form>

        <p class="text-center text-sm mt-4" style="color: var(--text-secondary);">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login di sini</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
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
