<div x-data="userMgmtAnim()" x-init="init()">

    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Kelola User</h1>
            <p class="pm-sub">Manajemen akun dan role pengguna sistem</p>
        </div>
        <div style="display:flex;gap:0.6rem;">
            <button wire:click="export" class="pm-btn pm-btn-ghost">
                <i data-lucide="download" style="width:15px;height:15px;"></i> Export CSV
            </button>
            <button wire:click="openCreate" class="pm-btn pm-btn-primary">
                <i data-lucide="user-plus" style="width:15px;height:15px;"></i> Tambah User
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="pm-flash pm-flash-success" x-data x-init="$el.style.opacity = '0'; anime && anime({targets: $el, opacity: [0,1], translateY: ['-8px','0px'], duration: 380, easing: 'easeOutBack'})">
        <i data-lucide="check-circle" style="width:15px;height:15px;"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="pm-toolbar" data-anim="fade-up" data-delay="80">
        <div class="pm-search-wrap">
            <i data-lucide="search" class="pm-search-icon"></i>
            <input type="text" wire:model.live.debounce.300ms="search" class="pm-search" placeholder="Nama, email, NIM…">
            @if($search)
            <button wire:click="$set('search','')" class="pm-search-clear"><i data-lucide="x" style="width:13px;height:13px;"></i></button>
            @endif
        </div>
        <div class="pm-filters">
            <select wire:model.live="filterRole" class="pm-select">
                <option value="">Semua Role</option>
                <option value="reporter">Pelapor</option>
                <option value="admin">Admin</option>
                <option value="pimpinan">Pimpinan</option>
                <option value="spmi">SPMI</option>
                <option value="pj_area">PJ Area</option>
            </select>
            <select wire:model.live="perPage" class="pm-select" style="width:90px;">
                <option value="15">15</option><option value="25">25</option><option value="50">50</option>
            </select>
        </div>
    </div>

    {{-- Form --}}
    @if($showForm)
        <div class="pm-card" style="margin-bottom:1.25rem;" data-anim="fade-up" data-delay="60">
            <div class="pm-card-header">
                <h3>{{ $formMode === 'edit' ? 'Edit User' : 'Tambah User Baru' }}</h3>
                <button wire:click="closeForm" class="pm-btn pm-btn-ghost pm-btn-icon"><i data-lucide="x" style="width:14px;height:14px;"></i></button>
            </div>
            <div class="pm-card-body">
                @php
                    $m = $formMode === 'edit';
                    $nameField = $m ? 'editName' : 'createName';
                    $emailField = $m ? 'editEmail' : 'createEmail';
                    $passwordField = $m ? 'editPassword' : 'createPassword';
                    $roleField = $m ? 'editRole' : 'createRole';
                    $userTypeField = $m ? 'editUserType' : 'createUserType';
                    $showNameField = $m ? 'editShowNameOnLanding' : 'createShowNameOnLanding';
                    $cr = $$roleField;
                    $camps = $m ? $this->editLocationCampuses : $this->createLocationCampuses;
                    $brs = $m ? $this->editLocationBranches : $this->createLocationBranches;
                    $fls = $m ? $this->editLocationFloors : $this->createLocationFloors;
                    $sps = $m ? $this->editLocationSpaces : $this->createLocationSpaces;
                    $cbt = $m ? $this->editLocationBranchType : $this->createLocationBranchType;
                @endphp
                <form wire:submit="saveForm">
                    <div class="pm-grid-3" style="gap:1rem;">
                        <div class="pm-form-group">
                            <label class="pm-label">Nama Lengkap</label>
                            <input type="text" wire:model="{{ $nameField }}" class="pm-input" placeholder="Nama lengkap">
                            @error($nameField)<p class="pm-form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="pm-form-group">
                            <label class="pm-label">Email</label>
                            <input type="email" wire:model="{{ $emailField }}" class="pm-input" placeholder="email@polman.ac.id">
                            @error($emailField)<p class="pm-form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="pm-form-group">
                            <label class="pm-label">Password {{ $m ? '(opsional)' : '' }}</label>
                            <input type="password" wire:model="{{ $passwordField }}" class="pm-input" placeholder="{{ $m ? 'Biarkan kosong jika tidak diubah' : 'Min. 8 karakter' }}">
                            @error($passwordField)<p class="pm-form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="pm-grid-2" style="gap:1rem;">
                        <div class="pm-form-group">
                            <label class="pm-label">Tipe User</label>
                            <select wire:model="{{ $userTypeField }}" class="pm-select-full">
                                <option value="umum">Umum</option>
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen</option>
                            </select>
                        </div>
                        <div class="pm-form-group">
                            <label class="pm-label">Role</label>
                            <select wire:model="{{ $roleField }}" wire:change="$refresh" class="pm-select-full">
                                <option value="reporter">Pelapor</option>
                                <option value="admin">Admin</option>
                                <option value="pimpinan">Pimpinan</option>
                                <option value="spmi">SPMI / Auditor</option>
                                <option value="pj_area">PJ Area</option>
                            </select>
                            @error($roleField)<p class="pm-form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    @if($cr === 'reporter')
                        <div class="pm-form-group">
                            <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;font-size:0.84rem;color:var(--pm-text-m);">
                                <input type="checkbox" wire:model="{{ $showNameField }}" style="accent-color:#5588A3;width:14px;height:14px;">
                                Tampilkan nama di landing page
                            </label>
                            <p class="pm-form-help">Jika dimatikan, tampil sebagai "Anonim" di leaderboard publik.</p>
                        </div>
                    @endif
                    @if(in_array($cr, ['pj_area','pimpinan','spmi']))
                        <div class="pm-grid-2" style="gap:1rem;margin-top:0.4rem;">
                            <div class="pm-form-group">
                                <label class="pm-label">Kampus</label>
                                <select wire:model.live="{{ $m ? 'editLocationCampusId' : 'createLocationCampusId' }}" wire:change="$refresh" class="pm-select-full">
                                    <option value="">Semua Area</option>
                                    @foreach($camps as $c)
                                        <option value="{{ $c->id }}">{{ $c->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pm-form-group">
                                <label class="pm-label">Gedung / Infrastruktur</label>
                                <select wire:model.live="{{ $m ? 'editLocationBranchId' : 'createLocationBranchId' }}" wire:change="$refresh" class="pm-select-full">
                                    <option value="">Semua Gedung</option>
                                    @foreach($brs as $b)
                                        <option value="{{ $b->id }}">{{ $b->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($cbt === 'gedung')
                            <div class="pm-grid-2" style="gap:1rem;">
                                <div class="pm-form-group">
                                    <label class="pm-label">Lantai</label>
                                    <select wire:model.live="{{ $m ? 'editLocationFloorId' : 'createLocationFloorId' }}" wire:change="$refresh" class="pm-select-full">
                                        <option value="all">Semua Lantai</option>
                                        @foreach($fls as $f)
                                            <option value="{{ $f->id }}">{{ $f->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pm-form-group">
                                    <label class="pm-label">Ruangan</label>
                                    <select wire:model.live="{{ $m ? 'editLocationSpaceId' : 'createLocationSpaceId' }}" class="pm-select-full">
                                        <option value="">Semua Ruangan</option>
                                        @foreach($sps as $s)
                                            <option value="{{ $s->id }}">{{ $s->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div style="display:flex;gap:0.6rem;margin-top:0.5rem;">
                        <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled">
                            <i data-lucide="save" style="width:14px;height:14px;"></i>
                            <span wire:loading.remove>Simpan</span>
                            <span wire:loading>Menyimpan…</span>
                        </button>
                        <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="pm-card" data-anim="fade-up" data-delay="120">
        <div wire:loading wire:target="search,filterRole,sort,perPage,openEdit,saveForm,deleteUser" class="pm-loading-bar"></div>
        <div class="pm-table-wrap">
            <table class="pm-table" id="user-table">
                <thead>
                    <tr>
                        <th class="pm-th-sort" wire:click="sort('full_name')">Nama @include('components.sort-icon',['col'=>'full_name','sortBy'=>$sortBy,'sortDir'=>$sortDir])</th>
                        <th>Email</th>
                        <th class="pm-th-sort" wire:click="sort('user_type')">Tipe @include('components.sort-icon',['col'=>'user_type','sortBy'=>$sortBy,'sortDir'=>$sortDir])</th>
                        <th>ID</th>
                        <th>Area Tugas</th>
                        <th class="pm-th-sort" wire:click="sort('role')">Role @include('components.sort-icon',['col'=>'role','sortBy'=>$sortBy,'sortDir'=>$sortDir])</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="pm-row" wire:key="u-{{ $user->id }}">
                            <td>
                                <div style="display:flex;align-items:center;gap:0.6rem;">
                                    <div class="pm-mini-avatar">{{ strtoupper(substr($user->full_name,0,2)) }}</div>
                                    <span style="font-weight:600;font-size:0.87rem;">{{ $user->full_name }}</span>
                                </div>
                            </td>
                            <td style="font-size:0.78rem;color:var(--pm-text-m);">{{ $user->email }}</td>
                            <td><span class="pm-badge {{ $user->user_type==='mahasiswa'?'pm-badge-info':($user->user_type==='dosen'?'pm-badge-warning':'pm-badge-neutral') }}">{{ $user->user_type_label }}</span></td>
                            <td style="font-size:0.75rem;font-family:monospace;color:var(--pm-text-d);">{{ $user->isMahasiswa()?$user->nim:($user->isDosen()?$user->nomor_dosen:'—') }}</td>
                            <td style="font-size:0.76rem;color:var(--pm-text-d);max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ in_array($user->role,['pj_area','pimpinan','spmi'])?($user->assigned_location_labels?:'Semua Area'):'—' }}</td>
                            <td><span class="pm-badge {{ match($user->role){'admin'=>'pm-badge-danger','pimpinan'=>'pm-badge-info','spmi'=>'pm-badge-warning','pj_area'=>'pm-badge-success',default=>'pm-badge-accent'} }}">{{ $user->role==='pj_area'?'PJ Area':ucfirst($user->role) }}</span></td>
                            <td style="text-align:right;">
                                <div class="pm-action-group">
                                    <button wire:click="openEdit({{ $user->id }})" class="pm-btn pm-btn-outline pm-btn-sm">
                                        <i data-lucide="pencil" style="width:12px;height:12px;"></i> Edit
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="deleteUser({{ $user->id }})" onclick="return confirm('Hapus user {{ $user->full_name }}?')" class="pm-btn pm-btn-danger pm-btn-sm pm-btn-icon" title="Hapus">
                                            <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="pm-empty"><div class="pm-empty-icon"><i data-lucide="users" style="width:30px;height:30px;"></i></div><p>Belum ada user.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pm-table-footer">
            <span class="pm-count">@if($users->total() > 0){{ $users->firstItem() }}–{{ $users->lastItem() }} dari <strong>{{ $users->total() }}</strong> user@else Tidak ada hasil @endif</span>
            @if($users->hasPages()) {{ $users->links() }} @endif
        </div>
    </div>

    <script>
        function userMgmtAnim() {
            return {
                init() {
                    this.$nextTick(() => { this.entrance(); this.watch(); });
                },
                entrance() {
                    const h = document.querySelector('[data-anim="slide-down"]');
                    if (h && typeof anime !== 'undefined') {
                        h.style.opacity = '0'; h.style.transform = 'translateY(-18px)';
                        anime({targets:h, opacity:[0,1], translateY:['-18px','0px'], duration:500, easing:'easeOutExpo'});
                    }
                    document.querySelectorAll('[data-anim="fade-up"]').forEach(el => {
                        el.style.opacity = '0'; el.style.transform = 'translateY(16px)';
                        anime({targets:el, opacity:[0,1], translateY:['16px','0px'], duration:440, delay:parseInt(el.dataset.delay||0), easing:'easeOutCubic'});
                    });
                    this.animRows();
                },
                animRows() {
                    const rows = document.querySelectorAll('#user-table tbody tr:not([data-rowed])');
                    if (!rows.length || typeof anime === 'undefined') return;
                    rows.forEach(r => r.dataset.rowed = '1');
                    anime({ targets:Array.from(rows), opacity:[0,1], translateX:['-12px','0px'], duration:360, delay:anime.stagger(35), easing:'easeOutCubic' });
                    const avs = document.querySelectorAll('#user-table .pm-mini-avatar:not([data-avanim])');
                    avs.forEach(a => a.dataset.avanim = '1');
                    anime({ targets:Array.from(avs), scale:[0,1], opacity:[0,1], duration:350, delay:anime.stagger(30,{start:200}), easing:'easeOutBack' });
                },
                watch() {
                    if (typeof Livewire === 'undefined') return;
                    Livewire.hook('morph.updated', () => {
                        document.querySelectorAll('#user-table tbody tr').forEach(r => delete r.dataset.rowed);
                        document.querySelectorAll('#user-table .pm-mini-avatar').forEach(a => delete a.dataset.avanim);
                        setTimeout(() => { this.animRows(); if(window.lucide) lucide.createIcons(); }, 40);
                    });
                }
            }
        }
    </script>
</div>
