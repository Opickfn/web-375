<div>
    <div class="page-header">
        <h1>Profil Saya</h1>
        <p>Kelola informasi akun dan keamanan Anda</p>
    </div>

    <div class="grid grid-3 gap-6">
        {{-- Left: Profile Card --}}
        <div>
            <div class="card animate-in">
                <div class="card-body" style="text-align:center;padding:40px 24px;">
                    <div class="profile-avatar-large">
                        {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                    </div>
                    <h3 style="margin-top:16px;">{{ Auth::user()->full_name }}</h3>
                    <p class="text-sm text-muted" style="margin-top:4px;">{{ Auth::user()->email }}</p>
                    <div style="margin-top:12px;display:flex;justify-content:center;gap:8px;">
                        <span class="badge {{ match(Auth::user()->role) { 'admin' => 'badge-danger', 'manager' => 'badge-warning', default => 'badge-primary' } }}">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                        <span class="badge {{ match(Auth::user()->user_type) { 'mahasiswa' => 'badge-info', 'dosen' => 'badge-warning', default => 'badge-neutral' } }}">
                            {{ ucfirst(Auth::user()->user_type) }}
                        </span>
                    </div>

                    @if(Auth::user()->isMahasiswa())
                    <div style="margin-top:24px;text-align:left;border-top:1px solid var(--border-light);padding-top:20px;">
                        <div class="profile-detail">
                            <span class="profile-detail-label">NIM</span>
                            <span class="profile-detail-value">{{ Auth::user()->nim }}</span>
                        </div>
                        <div class="profile-detail">
                            <span class="profile-detail-label">Gedung</span>
                            <span class="profile-detail-value">{{ Auth::user()->gedung }}</span>
                        </div>
                        <div class="profile-detail">
                            <span class="profile-detail-label">Ruangan</span>
                            <span class="profile-detail-value">{{ Auth::user()->ruangan }}</span>
                        </div>
                        <div class="profile-detail">
                            <span class="profile-detail-label">Kelas</span>
                            <span class="profile-detail-value">{{ Auth::user()->kelas }}</span>
                        </div>
                        <div class="profile-detail">
                            <span class="profile-detail-label">Angkatan</span>
                            <span class="profile-detail-value">{{ Auth::user()->tahun_angkatan }}</span>
                        </div>
                    </div>
                    @elseif(Auth::user()->isDosen())
                    <div style="margin-top:24px;text-align:left;border-top:1px solid var(--border-light);padding-top:20px;">
                        <div class="profile-detail">
                            <span class="profile-detail-label">Nomor Dosen</span>
                            <span class="profile-detail-value">{{ Auth::user()->nomor_dosen }}</span>
                        </div>
                        <div class="profile-detail">
                            <span class="profile-detail-label">Jabatan</span>
                            <span class="profile-detail-value">{{ Auth::user()->jabatan }}</span>
                        </div>
                    </div>
                    @endif

                    @if(Auth::user()->canCreateReport())
                    <div style="margin-top:24px;border-top:1px solid var(--border-light);padding-top:20px;">
                        <div class="profile-stat-row">
                            <div class="profile-stat">
                                <div class="profile-stat-value">{{ Auth::user()->reports()->count() }}</div>
                                <div class="profile-stat-label">Laporan</div>
                            </div>
                            <div class="profile-stat">
                                <div class="profile-stat-value">{{ Auth::user()->totalPoints() }}</div>
                                <div class="profile-stat-label">Poin</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Edit Forms --}}
        <div style="grid-column: span 2;">
            {{-- Edit Profile Form --}}
            <div class="card animate-in mb-6" style="animation-delay:.1s">
                <div class="card-header">
                    <h3>
                        <i data-lucide="user" style="width:18px;height:18px;display:inline;vertical-align:middle;margin-right:8px;"></i>
                        Informasi Profil
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    <form wire:submit="updateProfile">
                        <div class="grid grid-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" wire:model="full_name" class="form-input" placeholder="Nama lengkap">
                                @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model="email" class="form-input" placeholder="email@polman.ac.id">
                                @error('email') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" wire:model="phone" class="form-input" placeholder="08xxxxxxxxxx">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Mahasiswa Fields --}}
                        @if(Auth::user()->isMahasiswa())
                        <div style="border-top:1px solid var(--border-light);margin:24px 0 20px;padding-top:20px;">
                            <p class="text-sm font-semibold text-muted mb-4">Data Mahasiswa</p>
                            <div class="grid grid-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">NIM</label>
                                    <input type="text" wire:model="nim" class="form-input" placeholder="221511001">
                                    @error('nim') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kelas</label>
                                    <input type="text" wire:model="kelas" class="form-input" placeholder="2A">
                                    @error('kelas') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Gedung</label>
                                <select wire:model="gedung" class="form-select" id="profileGedung" onchange="loadProfileRuangan()">
                                    <option value="">Pilih gedung</option>
                                    @foreach($gedungs as $g)
                                        <option value="{{ $g->nama }}" data-id="{{ $g->id }}">{{ $g->full_name }}</option>
                                    @endforeach
                                </select>
                                @error('gedung') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Ruangan</label>
                                    <select wire:model="ruangan" class="form-select" id="profileRuangan">
                                        <option value="">Pilih ruangan</option>
                                        @if($ruangan)
                                        <option value="{{ $ruangan }}" selected>{{ $ruangan }}</option>
                                        @endif
                                    </select>
                                    @error('ruangan') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tahun Angkatan</label>
                                    <input type="text" wire:model="tahun_angkatan" class="form-input" placeholder="2022" maxlength="4">
                                    @error('tahun_angkatan') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Dosen Fields --}}
                        @if(Auth::user()->isDosen())
                        <div style="border-top:1px solid var(--border-light);margin:24px 0 20px;padding-top:20px;">
                            <p class="text-sm font-semibold text-muted mb-4">Data Dosen</p>
                            <div class="grid grid-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Nomor Dosen (NIDN/NIP)</label>
                                    <input type="text" wire:model="nomor_dosen" class="form-input" placeholder="0012345678">
                                    @error('nomor_dosen') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" wire:model="jabatan" class="form-input" placeholder="Lektor / Dosen Tetap">
                                    @error('jabatan') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" style="width:16px;height:16px;"></i>
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Change Password Form --}}
            <div class="card animate-in" style="animation-delay:.2s">
                <div class="card-header">
                    <h3>
                        <i data-lucide="lock" style="width:18px;height:18px;display:inline;vertical-align:middle;margin-right:8px;"></i>
                        Ubah Password
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('password_success'))
                    <div class="alert alert-success">
                        <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                        {{ session('password_success') }}
                    </div>
                    @endif

                    <form wire:submit="updatePassword">
                        <div class="form-group">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" wire:model="current_password" class="form-input" placeholder="Masukkan password saat ini">
                            @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Password Baru</label>
                                <input type="password" wire:model="new_password" class="form-input" placeholder="Minimal 8 karakter">
                                @error('new_password') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" wire:model="new_password_confirmation" class="form-input" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="key" style="width:16px;height:16px;"></i>
                            Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    async function loadProfileRuangan() {
        const sel = document.getElementById('profileGedung');
        const opt = sel.options[sel.selectedIndex];
        const gedungId = opt?.dataset?.id;
        const ruanganSelect = document.getElementById('profileRuangan');

        if (!gedungId) {
            ruanganSelect.innerHTML = '<option value="">Pilih ruangan</option>';
            return;
        }

        ruanganSelect.innerHTML = '<option value="">Memuat...</option>';
        try {
            const res = await fetch(`/api/gedung/${gedungId}/ruangan`);
            const data = await res.json();
            ruanganSelect.innerHTML = '<option value="">Pilih ruangan</option>';
            data.forEach(r => {
                const o = document.createElement('option');
                o.value = r.label;
                o.textContent = r.label;
                ruanganSelect.appendChild(o);
            });
        } catch {
            ruanganSelect.innerHTML = '<option value="">Gagal memuat</option>';
        }
    }
</script>
@endscript
