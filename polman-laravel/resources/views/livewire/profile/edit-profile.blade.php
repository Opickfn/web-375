<div x-data="profileAnim()" x-init="init()">

    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Profil & Pengaturan</h1>
            <p class="pm-sub">Kelola informasi akun dan keamanan Anda</p>
        </div>
        <a href="{{ route('dashboard') }}" class="pm-btn pm-btn-ghost">
            <i data-lucide="arrow-left" style="width:15px;height:15px;"></i> Dashboard
        </a>
    </div>

    <div style="display:grid;grid-template-columns:300px 1fr;gap:1.25rem;align-items:start;"
        class="profile-layout">

        {{-- ── Left: Identity Card ── --}}
        <div id="profile-identity-card" data-anim="card-in">
            <div class="pm-card" style="overflow:hidden;">
                {{-- Top color strip --}}
                <div style="height:80px;background:linear-gradient(135deg,#00334E,#145374,#5588A3);position:relative;">
                    <div style="position:absolute;bottom:-36px;left:50%;transform:translateX(-50%);">
                        <div id="profile-avatar" style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#145374,#5588A3);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;color:#fff;border:4px solid #00334E;box-shadow:0 0 24px rgba(85,136,163,0.5);">
                            {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div style="padding:2.8rem 1.4rem 1.4rem;text-align:center;">
                    <div style="font-size:1.05rem;font-weight:800;color:--pm-text;margin-bottom:4px;">{{ Auth::user()->full_name }}</div>
                    <div style="font-size:0.78rem;color:--pm-color-text-secondary;margin-bottom:1rem;">{{ Auth::user()->email }}</div>

                    <div style="display:flex;justify-content:center;gap:0.45rem;flex-wrap:wrap;margin-bottom:1.2rem;">
                        <span class="pm-badge {{ match(Auth::user()->role){'admin'=>'pm-badge-danger','pimpinan'=>'pm-badge-info','spmi'=>'pm-badge-warning','pj_area'=>'pm-badge-success',default=>'pm-badge-accent'} }}">
                            {{ Auth::user()->role==='pj_area'?'PJ Area':ucfirst(Auth::user()->role) }}
                        </span>
                        <span class="pm-badge {{ Auth::user()->user_type==='mahasiswa'?'pm-badge-info':(Auth::user()->user_type==='dosen'?'pm-badge-warning':'pm-badge-neutral') }}">
                            {{ ucfirst(Auth::user()->user_type) }}
                        </span>
                    </div>

                    @if(Auth::user()->canCreateReport())
                    {{-- Stats ── --}}
                    <div style="display:flex;justify-content:center;gap:1.5rem;padding:1rem 0;border-top:1px solid rgba(20,83,116,0.4);border-bottom:1px solid rgba(20,83,116,0.4);margin-bottom:1rem;">
                        <div style="text-align:center;">
                            <div style="font-size:1.5rem;font-weight:800;color:#5588A3;" id="stat-reports">{{ Auth::user()->reports()->count() }}</div>
                            <div style="font-size:0.7rem;color:rgba(232,232,232,0.4);margin-top:2px;text-transform:uppercase;letter-spacing:0.06em;">Laporan</div>
                        </div>
                        <div style="text-align:center;">
                            <div style="font-size:1.5rem;font-weight:800;color:#f59e0b;" id="stat-points">{{ Auth::user()->totalPoints() }}</div>
                            <div style="font-size:0.7rem;color:rgba(232,232,232,0.4);margin-top:2px;text-transform:uppercase;letter-spacing:0.06em;">Poin</div>
                        </div>
                    </div>
                    @endif

                    {{-- Quick links ── --}}
                    <div style="display:flex;flex-direction:column;gap:0.4rem;">
                        @if(Auth::user()->canCreateReport())
                        <a href="{{ route('points.my') }}" class="pm-btn pm-btn-ghost" style="justify-content:flex-start;font-size:0.82rem;">
                            <i data-lucide="star" style="width:14px;height:14px;color:#f59e0b;"></i> Lihat Riwayat Poin
                        </a>
                        @endif
                        <a href="{{ route('leaderboard') }}" class="pm-btn pm-btn-ghost" style="justify-content:flex-start;font-size:0.82rem;">
                            <i data-lucide="trophy" style="width:14px;height:14px;color:#5588A3;"></i> Leaderboard
                        </a>
                    </div>
                </div>

                {{-- Identity detail ── --}}
                @if(Auth::user()->isMahasiswa())
                <div style="border-top:1px solid rgba(20,83,116,0.4);padding:1rem 1.4rem;">
                    <div style="font-size:0.65rem;font-weight:700;color:--pm-color-text-secondary;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;">Data Mahasiswa</div>
                    @foreach([['NIM',$user->nim??'—'],['Kelas',$user->kelas??'—'],['Jurusan',$user->gedung??'—'],['Prodi',$user->ruangan??'—'],['Angkatan',$user->tahun_angkatan??'—']] as [$label,$val])
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid rgba(20,83,116,0.2);">
                        <span style="font-size:0.73rem;color:--pm-color-text-secondary;">{{ $label }}</span>
                        <span style="font-size:0.78rem;font-weight:600;">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
                @elseif(Auth::user()->isDosen())
                <div style="border-top:1px solid rgba(20,83,116,0.4);padding:1rem 1.4rem;">
                    <div style="font-size:0.65rem;font-weight:700;color:rgba(232,232,232,0.3);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;">Data Dosen</div>
                    @foreach([['NIDN/NIP',$user->nomor_dosen??'—'],['Jabatan',$user->jabatan??'—']] as [$label,$val])
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid rgba(20,83,116,0.2);">
                        <span style="font-size:0.73rem;color:rgba(232,232,232,0.4);">{{ $label }}</span>
                        <span style="font-size:0.78rem;font-weight:600;">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── Right: Forms ── --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Profile info form --}}
            <div class="pm-card" data-anim="fade-right" data-delay="100">
                <div class="pm-card-header">
                    <h3><i data-lucide="user" style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:6px;color:#5588A3;"></i>Informasi Profil</h3>
                    @if(session('success'))
                    <span class="pm-badge pm-badge-success">
                        <i data-lucide="check" style="width:11px;height:11px;"></i> Tersimpan
                    </span>
                    @endif
                </div>
                <div class="pm-card-body">
                    @if(session('success'))
                    <div class="pm-flash pm-flash-success" style="margin-bottom:1rem;"
                        x-init="if(typeof anime!=='undefined'){$el.style.opacity=0;anime({targets:$el,opacity:[0,1],translateY:['-8px','0px'],duration:380,easing:'easeOutBack'})}">
                        <i data-lucide="check-circle" style="width:15px;height:15px;"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    <form wire:submit="updateProfile">
                        <div class="pm-grid-2" style="gap:1rem;">
                            <div class="pm-form-group">
                                <label class="pm-label">Nama Lengkap</label>
                                <input type="text" wire:model="full_name" class="pm-input" placeholder="Nama lengkap">
                                @error('full_name') <p class="pm-form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="pm-form-group">
                                <label class="pm-label">Alamat Email</label>
                                <input type="email" wire:model="email" class="pm-input" placeholder="email@polman.ac.id">
                                @error('email') <p class="pm-form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="pm-form-group">
                            <label class="pm-label">Nomor Telepon <span style="color:var(--pm-text-d);font-weight:400;">(opsional)</span></label>
                            <input type="text" wire:model="phone" class="pm-input" placeholder="08xxxxxxxxxx">
                        </div>

                        @if(Auth::user()->role === 'reporter')
                        <div class="pm-form-group" style="padding:0.85rem 1rem;background:rgba(85,136,163,0.08);border:1px solid rgba(85,136,163,0.2);border-radius:10px;">
                            <label style="display:flex;align-items:center;gap:0.65rem;cursor:pointer;">
                                <input type="checkbox" 
                                       id="show_name_landing" 
                                       wire:model.live="show_name_on_landing" 
                                       style="accent-color:#5588A3;width:16px;height:16px;">
                                <div>
                                    <div style="font-size:0.85rem;font-weight:600;color:var(--pm-text);">Tampilkan nama di landing page</div>
                                    <div style="font-size:0.73rem;color:var(--pm-text-d);margin-top:1px;">Jika dimatikan, nama Anda tampil sebagai "Anonim" di leaderboard publik. <span wire:loading wire:target="show_name_on_landing" style="opacity:0.7;">Menyimpan...</span></div>
                                </div>
                            </label>
                        </div>
                        @endif

                        {{-- Mahasiswa fields --}}
                        @if(Auth::user()->isMahasiswa())
                        <div style="border-top:1px solid rgba(20,83,116,0.4);margin:1rem 0;padding-top:1rem;">
                            <div style="font-size:0.65rem;font-weight:700;color:rgba(232,232,232,0.3);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;">Data Mahasiswa</div>
                            <div class="pm-grid-2" style="gap:1rem;">
                                <div class="pm-form-group"><label class="pm-label">NIM</label><input type="text" wire:model="nim" class="pm-input" placeholder="221511001">@error('nim')<p class="pm-form-error">{{ $message }}</p>@enderror</div>
                                <div class="pm-form-group"><label class="pm-label">Kelas</label><input type="text" wire:model="kelas" class="pm-input" placeholder="2A">@error('kelas')<p class="pm-form-error">{{ $message }}</p>@enderror</div>
                            </div>
                            <div class="pm-form-group"><label class="pm-label">Jurusan</label><input type="text" wire:model="gedung" class="pm-input" placeholder="Teknik Mesin"></div>
                            <div class="pm-grid-2" style="gap:1rem;">
                                <div class="pm-form-group"><label class="pm-label">Program Studi</label><input type="text" wire:model="ruangan" class="pm-input" placeholder="Teknik Produksi"></div>
                                <div class="pm-form-group"><label class="pm-label">Tahun Angkatan</label><input type="text" wire:model="tahun_angkatan" class="pm-input" placeholder="2022" maxlength="4">@error('tahun_angkatan')<p class="pm-form-error">{{ $message }}</p>@enderror</div>
                            </div>
                        </div>
                        @endif

                        {{-- Dosen fields --}}
                        @if(Auth::user()->isDosen())
                        <div style="border-top:1px solid rgba(20,83,116,0.4);margin:1rem 0;padding-top:1rem;">
                            <div style="font-size:0.65rem;font-weight:700;color:rgba(232,232,232,0.3);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;">Data Dosen</div>
                            <div class="pm-grid-2" style="gap:1rem;">
                                <div class="pm-form-group"><label class="pm-label">NIDN / NIP</label><input type="text" wire:model="nomor_dosen" class="pm-input" placeholder="0012345678">@error('nomor_dosen')<p class="pm-form-error">{{ $message }}</p>@enderror</div>
                                <div class="pm-form-group"><label class="pm-label">Jabatan</label><input type="text" wire:model="jabatan" class="pm-input" placeholder="Lektor / Dosen Tetap"></div>
                            </div>
                        </div>
                        @endif

                        <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled"
                            @mouseenter="hoverBtn($el)" @mouseleave="unhoverBtn($el)">
                            <i data-lucide="save" style="width:14px;height:14px;"></i>
                            <span wire:loading.remove>Simpan Perubahan</span>
                            <span wire:loading>Menyimpan…</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Password form --}}
            <div class="pm-card" data-anim="fade-right" data-delay="180">
                <div class="pm-card-header">
                    <h3><i data-lucide="shield" style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:6px;color:#5588A3;"></i>Keamanan Akun</h3>
                    @if(session('password_success'))
                    <span class="pm-badge pm-badge-success">
                        <i data-lucide="check" style="width:11px;height:11px;"></i> Berhasil diubah
                    </span>
                    @endif
                </div>
                <div class="pm-card-body">
                    @if(session('password_success'))
                    <div class="pm-flash pm-flash-success" style="margin-bottom:1rem;"
                        x-init="if(typeof anime!=='undefined'){$el.style.opacity=0;anime({targets:$el,opacity:[0,1],translateY:['-8px','0px'],duration:380,easing:'easeOutBack'})}">
                        <i data-lucide="check-circle" style="width:15px;height:15px;"></i>
                        {{ session('password_success') }}
                    </div>
                    @endif

                    <form wire:submit="updatePassword">
                        <div class="pm-form-group">
                            <label class="pm-label">Password Saat Ini</label>
                            <div style="position:relative;">
                                <input type="password" wire:model="current_password" class="pm-input"
                                    placeholder="Masukkan password saat ini" id="cur-pwd">
                            </div>
                            @error('current_password') <p class="pm-form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="pm-grid-2" style="gap:1rem;">
                            <div class="pm-form-group">
                                <label class="pm-label">Password Baru</label>
                                <input type="password" wire:model="new_password" class="pm-input"
                                    placeholder="Minimal 8 karakter">
                                @error('new_password') <p class="pm-form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="pm-form-group">
                                <label class="pm-label">Konfirmasi Password Baru</label>
                                <input type="password" wire:model="new_password_confirmation" class="pm-input"
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>

                        {{-- Password strength hint --}}
                        <div style="padding:0.7rem 0.9rem;background:rgba(20,83,116,0.15);border-radius:8px;margin-bottom:1rem;">
                            <div style="font-size:0.72rem;color:--pm-color-text-secondary;display:flex;gap:1rem;flex-wrap:wrap;">
                                <span>✓ Min. 8 karakter</span>
                                <span>✓ Kombinasi huruf & angka</span>
                                <span>✓ Jangan gunakan info pribadi</span>
                            </div>
                        </div>

                        <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled"
                            @mouseenter="hoverBtn($el)" @mouseleave="unhoverBtn($el)">
                            <i data-lucide="key" style="width:14px;height:14px;"></i>
                            <span wire:loading.remove>Ubah Password</span>
                            <span wire:loading>Memperbarui…</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

@push('styles')
<style>
@media(max-width:900px) {
    .profile-layout { grid-template-columns: 1fr !important; }
}
</style>
@endpush

@push('scripts')
<script>
function profileAnim() {
    return {
        init() {
            this.$nextTick(() => {
                this.entrance();
                this.counters();
                this.inputGlow();
                if (typeof Livewire !== 'undefined') {
                    Livewire.hook('morph.updated', () => {
                        setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 30);
                    });
                }
            });
        },

        entrance() {
            // Header
            const h = document.querySelector('[data-anim="slide-down"]');
            if (h && typeof anime !== 'undefined') {
                h.style.opacity='0'; h.style.transform='translateY(-20px)';
                anime({ targets:h, opacity:[0,1], translateY:['-20px','0px'], duration:520, easing:'easeOutExpo' });
            }

            // Identity card
            const card = document.getElementById('profile-identity-card');
            if (card && typeof anime !== 'undefined') {
                card.style.opacity='0'; card.style.transform='translateX(-24px)';
                anime({ targets:card, opacity:[0,1], translateX:['-24px','0px'], duration:550, delay:100, easing:'easeOutExpo' });
            }

            // Avatar pop
            const avatar = document.getElementById('profile-avatar');
            if (avatar && typeof anime !== 'undefined') {
                avatar.style.transform='scale(0.5)';
                anime({ targets:avatar, scale:[0.5,1.1,1], duration:600, delay:350, easing:'easeOutElastic(1,0.5)' });
            }

            // Right forms slide in
            document.querySelectorAll('[data-anim="fade-right"]').forEach(el => {
                el.style.opacity='0'; el.style.transform='translateX(24px)';
                const delay = parseInt(el.dataset.delay||0);
                if (typeof anime !== 'undefined') {
                    anime({ targets:el, opacity:[0,1], translateX:['24px','0px'], duration:500, delay, easing:'easeOutCubic' });
                }
            });
        },

        counters() {
            if (typeof anime === 'undefined') return;
            ['stat-reports','stat-points'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const target = parseInt(el.textContent) || 0;
                if (target > 0) {
                    anime({
                        targets:{v:0}, v:[0,target], round:1, duration:1000, delay:600, easing:'easeOutExpo',
                        update: a => { el.textContent = Math.floor(a.animations[0].currentValue); }
                    });
                }
            });
        },

        inputGlow() {
            document.querySelectorAll('.pm-input, .pm-select-full').forEach(inp => {
                inp.addEventListener('focus', () => {
                    if (typeof anime !== 'undefined') anime({ targets:inp, boxShadow:'0 0 0 3px rgba(85,136,163,0.22)', duration:200 });
                });
                inp.addEventListener('blur', () => {
                    if (typeof anime !== 'undefined') anime({ targets:inp, boxShadow:'0 0 0 0px rgba(85,136,163,0)', duration:180 });
                });
            });
        },

        hoverBtn(el) {
            if (typeof anime !== 'undefined') anime({ targets:el, scale:1.04, duration:180, easing:'easeOutBack' });
        },
        unhoverBtn(el) {
            if (typeof anime !== 'undefined') anime({ targets:el, scale:1, duration:180, easing:'easeOutQuad' });
        }
    }
}
</script>
@endpush