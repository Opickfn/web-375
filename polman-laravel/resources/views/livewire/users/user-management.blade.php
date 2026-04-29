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
    <div class="pm-flash pm-flash-success" x-data
        x-init="$el.style.opacity='0'; typeof anime !== 'undefined' && anime({targets:$el, opacity:[0,1], translateY:['-8px','0px'], duration:380, easing:'easeOutBack'})">
        <i data-lucide="check-circle" style="width:15px;height:15px;"></i> {{ session('success') }}
    </div>
    @endif

    {{-- ── Toolbar ── --}}
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
                <option value="reporter">Pengusul</option>
                <option value="admin">Admin</option>
                <option value="pimpinan">Pimpinan</option>
                <option value="spmi">SPMI</option>
                <option value="pj_area">PJ Area</option>
            </select>
            <select wire:model.live="perPage" class="pm-select" style="width:90px;">
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    {{-- ── Create / Edit Form ── --}}
    @if($showForm)
    <div class="pm-card" style="margin-bottom:1.25rem;" data-anim="fade-up" data-delay="60" id="user-form-card">
        <div class="pm-card-header">
            <h3>{{ $formMode === 'edit' ? '✏️ Edit User' : '➕ Tambah User Baru' }}</h3>
            <button wire:click="closeForm" class="pm-btn pm-btn-ghost pm-btn-icon">
                <i data-lucide="x" style="width:14px;height:14px;"></i>
            </button>
        </div>
        <div class="pm-card-body">
            @php
                $isEdit        = $formMode === 'edit';
                $nameField     = $isEdit ? 'editName'              : 'createName';
                $emailField    = $isEdit ? 'editEmail'             : 'createEmail';
                $passField     = $isEdit ? 'editPassword'          : 'createPassword';
                $roleField     = $isEdit ? 'editRole'              : 'createRole';
                $typeField     = $isEdit ? 'editUserType'          : 'createUserType';
                $showNameField = $isEdit ? 'editShowNameOnLanding' : 'createShowNameOnLanding';
                $selField      = $isEdit ? 'editSelectedLocations' : 'createSelectedLocations';
                $currentRole   = $isEdit ? $editRole               : $createRole;
                $currentSel    = $isEdit ? $editSelectedLocations  : $createSelectedLocations;
            @endphp

            <form wire:submit="saveForm">
                {{-- Basic info --}}
                <div class="pm-grid-3" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Nama Lengkap</label>
                        <input type="text" wire:model="{{ $nameField }}" class="pm-input" placeholder="Nama lengkap">
                        @error($nameField) <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Email</label>
                        <input type="email" wire:model="{{ $emailField }}" class="pm-input" placeholder="email@polman.ac.id">
                        @error($emailField) <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '' }}</label>
                        <input type="password" wire:model="{{ $passField }}" class="pm-input"
                            placeholder="{{ $isEdit ? 'Biarkan kosong jika tidak diubah' : 'Min. 8 karakter' }}">
                        @error($passField) <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Tipe User</label>
                        <select wire:model="{{ $typeField }}" class="pm-select-full">
                            <option value="umum">Umum</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                        </select>
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Role</label>
                        <select wire:model.live="{{ $roleField }}" class="pm-select-full">
                            <option value="reporter">Pengusul</option>
                            <option value="admin">Admin</option>
                            <option value="pimpinan">Pimpinan</option>
                            <option value="spmi">SPMI / Auditor</option>
                            <option value="pj_area">PJ Area</option>
                        </select>
                        @error($roleField) <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($currentRole === 'reporter')
                <div class="pm-form-group" style="padding:0.85rem 1rem;background:rgba(0,51,78,0.05);border:1px solid rgba(0,51,78,0.15);border-radius:10px;">
                    <label style="display:flex;align-items:center;gap:0.65rem;cursor:pointer;font-size:0.84rem;color:var(--pm-text-m);">
                        <input type="checkbox" wire:model="{{ $showNameField }}"
                            style="accent-color:#00334E;width:15px;height:15px;">
                        Tampilkan nama di landing page
                    </label>
                    <p class="pm-form-help">Jika dimatikan, tampil sebagai "Anonim" di leaderboard publik.</p>
                </div>
                @endif

                {{-- ── Multi-area Location Picker ── --}}
                @if(in_array($currentRole, ['pj_area', 'pimpinan', 'spmi']))
                <div class="pm-form-group" style="margin-top:0.75rem;">
                    <label class="pm-label" style="display:flex;align-items:center;justify-content:space-between;">
                        <span>
                            <i data-lucide="map-pin" style="width:13px;height:13px;display:inline;vertical-align:middle;margin-right:5px;"></i>
                            Area Tanggung Jawab
                            @php $selCount = count($currentSel); @endphp
                            @if($selCount > 0)
                            <span class="pm-badge pm-badge-success" style="margin-left:6px;font-size:0.65rem;">
                                {{ $selCount }} dipilih
                            </span>
                            @endif
                        </span>
                        @if($selCount > 0)
                        <button type="button"
                            wire:click="$set('{{ $selField }}', [])"
                            style="font-size:0.72rem;color:var(--pm-text-d);background:none;border:none;cursor:pointer;text-decoration:underline;">
                            Hapus semua pilihan
                        </button>
                        @endif
                    </label>
                    <p class="pm-form-help" style="margin-bottom:0.65rem;">
                        Centang satu atau lebih gedung / infrastruktur yang menjadi tanggung jawab user ini.
                        Biarkan kosong untuk akses semua area.
                    </p>

                    {{-- Location checkbox tree --}}
                    <div class="loc-tree-wrap">
                        @forelse($locationTree as $campus)
                        <div class="loc-campus-block" wire:key="campus-{{ $campus->id }}">
                            {{-- LEVEL 1: CAMPUS --}}
                            <button type="button" wire:click="toggleCampus({{ $campus->id }})" class="loc-campus-toggle">
                                <span class="loc-toggle-icon {{ in_array($campus->id, $expandedCampuses) ? 'open' : '' }}">
                                    <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                                </span>
                                <i data-lucide="map-pin" style="width:14px;height:14px;color:#5588A3;"></i>
                                <span class="loc-campus-name">{{ $campus->name }}</span>
                            </button>

                            @if(in_array($campus->id, $expandedCampuses))
                            <div class="loc-level-1-content" style="background: rgba(0,0,0,0.02);">
                                @foreach($campus->children as $branch)
                                <div wire:key="branch-{{ $branch->id }}">
                                    {{-- LEVEL 2: GEDUNG / INFRASTRUKTUR --}}
                                    <div class="loc-branch-row {{ in_array((string)$branch->id, $currentSel) ? 'checked' : '' }}">
                                        <button type="button" wire:click="toggleBranch({{ $branch->id }})" class="loc-sub-toggle">
                                            <i data-lucide="chevron-right" style="width:12px;height:12px;" class="{{ in_array($branch->id, $expandedBranches) ? 'rotate-90' : '' }}"></i>
                                        </button>
                                        <label class="loc-check-label">
                                            <input type="checkbox" value="{{ $branch->id }}" wire:model.live="{{ $selField }}" class="loc-checkbox">
                                            <span class="loc-branch-name"><i data-lucide="building-2" class="icon-inline"></i> {{ $branch->name }}</span>
                                        </label>
                                    </div>

                                    @if(in_array($branch->id, $expandedBranches))
                                    <div class="loc-level-2-content" style="padding-left: 2.5rem;">
                                        @foreach($branch->children as $floor)
                                        <div wire:key="floor-{{ $floor->id }}">
                                            {{-- LEVEL 3: LANTAI --}}
                                            <div class="loc-branch-row {{ in_array((string)$floor->id, $currentSel) ? 'checked' : '' }}">
                                                <button type="button" wire:click="toggleFloor({{ $floor->id }})" class="loc-sub-toggle">
                                                    <i data-lucide="chevron-right" style="width:12px;height:12px;" class="{{ in_array($floor->id, $expandedFloors) ? 'rotate-90' : '' }}"></i>
                                                </button>
                                                <label class="loc-check-label">
                                                    <input type="checkbox" value="{{ $floor->id }}" wire:model.live="{{ $selField }}" class="loc-checkbox">
                                                    <span class="loc-branch-name" style="font-weight:500; font-size:0.8rem;"><i data-lucide="layers" class="icon-inline"></i> {{ $floor->name }}</span>
                                                </label>
                                            </div>

                                            @if(in_array($floor->id, $expandedFloors))
                                            <div class="loc-level-3-content" style="padding-left: 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.2rem; padding-bottom: 0.5rem;">
                                                @foreach($floor->children as $room)
                                                {{-- LEVEL 4: RUANGAN / AREA --}}
                                                <label class="loc-room-item {{ in_array((string)$room->id, $currentSel) ? 'checked' : '' }}" wire:key="room-{{ $room->id }}">
                                                    <input type="checkbox" value="{{ $room->id }}" wire:model.live="{{ $selField }}" class="loc-checkbox">
                                                    <span class="loc-room-name"><i data-lucide="square" class="icon-inline" style="width:10px;"></i> {{ $room->name }}</span>
                                                </label>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="loc-empty">Belum ada data lokasi.</div>
                        @endforelse
                    </div>

                    {{-- Selected summary chips --}}
                    @if($selCount > 0)
                    <div style="margin-top:0.65rem;display:flex;flex-wrap:wrap;gap:0.35rem;">
                        @foreach($locationTree as $campus)
                            @foreach($campus->children as $branch)
                                @if(in_array((string)$branch->id, $currentSel))
                                <span class="pm-chip pm-chip-blue" style="font-size:0.7rem;">
                                    {{ $branch->name }}
                                    <button type="button"
                                        wire:click="$set('{{ $selField }}', array_values(array_filter({{ json_encode($currentSel) }}, fn(\$v) => \$v !== '{{ $branch->id }}')))"
                                        class="pm-chip-x">×</button>
                                </span>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                <div style="display:flex;gap:0.65rem;margin-top:1rem;">
                    <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled">
                        <i data-lucide="save" style="width:14px;height:14px;"></i>
                        <span wire:loading.remove>{{ $isEdit ? 'Perbarui' : 'Simpan' }}</span>
                        <span wire:loading>Menyimpan…</span>
                    </button>
                    <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ── User Table ── --}}
    <div class="pm-card" data-anim="fade-up" data-delay="120">
        <div wire:loading wire:target="search,filterRole,sort,perPage,openEdit,saveForm,deleteUser" class="pm-loading-bar"></div>
        <div class="pm-table-wrap">
            <table class="pm-table" id="user-table">
                <thead>
                    <tr>
                        <th class="pm-th-sort" wire:click="sort('full_name')">
                            Nama @include('components.sort-icon',['col'=>'full_name','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th>Email</th>
                        <th class="pm-th-sort" wire:click="sort('user_type')">
                            Tipe @include('components.sort-icon',['col'=>'user_type','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th>ID / NIM / NIDN</th>
                        <th>Area Tugas</th>
                        <th class="pm-th-sort" wire:click="sort('role')">
                            Role @include('components.sort-icon',['col'=>'role','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="pm-row" wire:key="u-{{ $user->id }}">
                        <td>
                            <div style="display:flex;align-items:center;gap:0.6rem;">
                                <div class="pm-mini-avatar">{{ strtoupper(substr($user->full_name, 0, 2)) }}</div>
                                <span style="font-weight:600;font-size:0.87rem;">{{ $user->full_name }}</span>
                            </div>
                        </td>
                        <td style="font-size:0.78rem;color:var(--pm-text-m);">{{ $user->email }}</td>
                        <td>
                            <span class="pm-badge {{ $user->user_type==='mahasiswa'?'pm-badge-info':($user->user_type==='dosen'?'pm-badge-warning':'pm-badge-neutral') }}">
                                {{ $user->user_type_label }}
                            </span>
                        </td>
                        <td style="font-size:0.75rem;font-family:monospace;color:var(--pm-text-d);">
                            {{ $user->isMahasiswa() ? $user->nim : ($user->isDosen() ? $user->nomor_dosen : '—') }}
                        </td>
                        <td>
                            @if(in_array($user->role, ['pj_area','pimpinan','spmi']))
                                @php $locs = $user->assignedLocations; @endphp
                                @if($locs->isEmpty())
                                    <span style="font-size:0.75rem;color:var(--pm-text-d);">Semua Area</span>
                                @else
                                    <div style="display:flex;flex-wrap:wrap;gap:0.3rem;max-width:220px;">
                                        @foreach($locs->take(3) as $loc)
                                        <span class="pm-badge pm-badge-neutral" style="font-size:0.62rem;padding:1px 6px;">
                                            {{ $loc->name }}
                                        </span>
                                        @endforeach
                                        @if($locs->count() > 3)
                                        <span class="pm-badge pm-badge-accent" style="font-size:0.62rem;padding:1px 6px;">
                                            +{{ $locs->count() - 3 }} lagi
                                        </span>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <span style="color:var(--pm-text-d);">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="pm-badge {{ match($user->role){ 'admin'=>'pm-badge-danger','pimpinan'=>'pm-badge-info','spmi'=>'pm-badge-warning','pj_area'=>'pm-badge-success', default=>'pm-badge-accent' } }}">
                                {{ $user->role === 'pj_area' ? 'PJ Area' : ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div class="pm-action-group">
                                <button wire:click="openEdit({{ $user->id }})" class="pm-btn pm-btn-outline pm-btn-sm">
                                    <i data-lucide="pencil" style="width:12px;height:12px;"></i> Edit
                                </button>
                                @if($user->id !== auth()->id())
                                <button wire:click="deleteUser({{ $user->id }})"
                                    onclick="return confirm('Hapus user {{ $user->full_name }}?')"
                                    class="pm-btn pm-btn-danger pm-btn-sm pm-btn-icon" title="Hapus">
                                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">
                        <div class="pm-empty">
                            <div class="pm-empty-icon"><i data-lucide="users" style="width:30px;height:30px;"></i></div>
                            <p>Belum ada user ditemukan.</p>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pm-table-footer">
            <span class="pm-count">
                @if($users->total() > 0)
                    {{ $users->firstItem() }}–{{ $users->lastItem() }} dari <strong>{{ $users->total() }}</strong> user
                @else
                    Tidak ada hasil
                @endif
            </span>
            @if($users->hasPages()) {{ $users->links() }} @endif
        </div>
    </div>

</div>

@push('styles')
<style>
/* ── Location Checkbox Tree ──────────────────────────── */
.loc-tree-wrap {
    border: 1px solid rgba(0,51,78,0.18);
    border-radius: 12px;
    overflow: hidden;
    background: rgba(255,255,255,0.7);
    max-height: 340px;
    overflow-y: auto;
}
.loc-tree-wrap::-webkit-scrollbar { width: 5px; }
.loc-tree-wrap::-webkit-scrollbar-thumb { background: rgba(0,51,78,0.2); border-radius: 3px; }

/* Campus accordion toggle */
.loc-campus-block {
    border-bottom: 1px solid rgba(0,51,78,0.1);
}
.loc-campus-block:last-child { border-bottom: none; }

.loc-campus-toggle {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 0.9rem;
    background: rgba(0,51,78,0.04);
    border: none;
    cursor: pointer;
    text-align: left;
    font-size: 0.82rem;
    font-weight: 700;
    color: #00334E;
    transition: background 0.18s ease;
}
.loc-campus-toggle:hover { background: rgba(0,51,78,0.08); }

.loc-toggle-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 5px;
    background: rgba(0,51,78,0.08);
    transition: transform 0.2s ease, background 0.2s ease;
    flex-shrink: 0;
}
.loc-toggle-icon.open {
    transform: rotate(90deg);
    background: rgba(0,51,78,0.14);
}

.loc-campus-name { flex: 1; }

.loc-campus-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 5px;
    border-radius: 99px;
    background: #00334E;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 800;
    margin-left: auto;
}

/* Branch checkboxes */
.loc-branches {
    padding: 0.4rem 0;
}

.loc-branch-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.5rem 0.9rem 0.5rem 1.6rem;
    cursor: pointer;
    transition: background 0.15s ease;
    border-radius: 0;
    position: relative;
}
.loc-branch-item:hover { background: rgba(0,51,78,0.05); }
.loc-branch-item.checked { background: rgba(0,51,78,0.08); }

.loc-checkbox {
    appearance: none;
    -webkit-appearance: none;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(0,51,78,0.3);
    border-radius: 4px;
    background: #fff;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.15s ease;
    position: relative;
}
.loc-checkbox:checked {
    background: #00334E;
    border-color: #00334E;
}
.loc-checkbox:checked::after {
    content: '';
    position: absolute;
    left: 3px;
    top: 1px;
    width: 5px;
    height: 8px;
    border: 2px solid #fff;
    border-top: none;
    border-left: none;
    transform: rotate(45deg);
}
.loc-checkbox:focus { outline: none; box-shadow: 0 0 0 3px rgba(0,51,78,0.15); }

.loc-branch-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 6px;
    flex-shrink: 0;
}
.loc-icon-gedung { background: rgba(245,158,11,0.12); color: #f59e0b; }
.loc-icon-infrastruktur { background: rgba(56,189,248,0.12); color: #38bdf8; }

.loc-branch-info {
    flex: 1;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.25rem;
    min-width: 0;
}
.loc-branch-name {
    font-size: 0.83rem;
    font-weight: 600;
    color: #00334E;
}
.loc-branch-type {
    font-size: 0.68rem;
    color: var(--pm-text-d);
    width: 100%;
}

.loc-check-mark {
    display: none;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #00334E;
    color: #fff;
    flex-shrink: 0;
}
.loc-branch-item.checked .loc-check-mark { display: inline-flex; }

.loc-empty {
    padding: 0.6rem 1.6rem;
    font-size: 0.75rem;
    color: var(--pm-text-d);
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-style: italic;
}
.loc-branch-row {
    display: flex;
    align-items: center;
    padding: 0.35rem 0.75rem;
    gap: 0.5rem;
    border-radius: 6px;
}
.loc-branch-row:hover { background: rgba(0,51,78,0.05); }
.loc-branch-row.checked { background: rgba(0,51,78,0.08); }

.loc-sub-toggle {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    display: flex;
    color: #5588A3;
}
.rotate-90 { transform: rotate(90deg); transition: transform 0.2s; }

.loc-check-label {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    flex: 1;
}

.loc-room-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    cursor: pointer;
    border-radius: 4px;
}
.loc-room-item:hover { background: rgba(0,51,78,0.04); }
.loc-room-item.checked { color: #00334E; font-weight: 600; background: rgba(0,51,78,0.08); }

.icon-inline {
    width: 13px;
    height: 13px;
    display: inline-block;
    vertical-align: middle;
    margin-right: 2px;
    opacity: 0.7;
}
</style>
@endpush

@push('scripts')
<script>
function userMgmtAnim() {
    return {
        init() {
            this.$nextTick(() => {
                this.entrance();
                this.watch();
            });
        },

        entrance() {
            const h = document.querySelector('[data-anim="slide-down"]');
            if (h && typeof anime !== 'undefined') {
                h.style.opacity = '0'; h.style.transform = 'translateY(-18px)';
                anime({ targets: h, opacity:[0,1], translateY:['-18px','0px'], duration:500, easing:'easeOutExpo' });
            }
            document.querySelectorAll('[data-anim="fade-up"]').forEach(el => {
                el.style.opacity = '0'; el.style.transform = 'translateY(16px)';
                if (typeof anime !== 'undefined') {
                    anime({ targets:el, opacity:[0,1], translateY:['16px','0px'], duration:440, delay:parseInt(el.dataset.delay||0), easing:'easeOutCubic' });
                }
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
            if (avs.length) {
                anime({ targets:Array.from(avs), scale:[0,1], opacity:[0,1], duration:350, delay:anime.stagger(30,{start:200}), easing:'easeOutBack' });
            }
        },

        watch() {
            if (typeof Livewire === 'undefined') return;
            Livewire.hook('morph.updated', () => {
                document.querySelectorAll('#user-table tbody tr').forEach(r => delete r.dataset.rowed);
                document.querySelectorAll('#user-table .pm-mini-avatar').forEach(a => delete a.dataset.avanim);
                setTimeout(() => {
                    this.animRows();
                    if (window.lucide) lucide.createIcons();
                }, 40);
            });
        }
    }
}
</script>
@endpush