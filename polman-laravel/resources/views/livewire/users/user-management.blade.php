<div>
    <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>Kelola User</h1>
            <p>Daftar semua user dan pengaturan role</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body flex justify-between flex-wrap items-center">
            <div class="flex gap-3">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-input" placeholder="Cari nama, email, atau NIM..." style="max-width:280px;">
                <select wire:model.live="filterRole" class="form-select" style="max-width:180px;">
                    <option value="">Semua Role</option>
                    <option value="reporter">Pelapor</option>
                    <option value="admin">Admin</option>
                    <option value="pimpinan">Pimpinan</option>
                    <option value="spmi">SPMI</option>
                    <option value="pj_area">PJ Area</option>
                </select>
            </div>
            <button wire:click="openCreate" class="btn btn-primary btn-sm">
                <i data-lucide="user-plus" style="width:16px;height:16px;"></i> Tambah User
            </button>
        </div>
    </div>

    {{-- Form Create / Edit --}}
    @if($showForm)
    <div class="card mb-4 animate-in">
        <div class="card-header"><h3>{{ $formMode === 'edit' ? 'Edit User' : 'Tambah User Baru' }}</h3></div>
        <div class="card-body">
            @php
                $mode = $formMode === 'edit' ? 'edit' : 'create';
                $currentRole = $formMode === 'edit' ? $editRole : $createRole;
                $currentLocationBranchType = $formMode === 'edit' ? $editLocationBranchType : $createLocationBranchType;
                $currentLocationBranchId = $formMode === 'edit' ? $editLocationBranchId : $createLocationBranchId;
                $currentLocationFloorId = $formMode === 'edit' ? $editLocationFloorId : $createLocationFloorId;
                $currentLocationCampusId = $formMode === 'edit' ? $editLocationCampusId : $createLocationCampusId;
                $currentLocationCampuses = $formMode === 'edit' ? $this->editLocationCampuses : $this->createLocationCampuses;
                $currentLocationBranches = $formMode === 'edit' ? $this->editLocationBranches : $this->createLocationBranches;
                $currentLocationFloors = $formMode === 'edit' ? $this->editLocationFloors : $this->createLocationFloors;
                $currentLocationSpaces = $formMode === 'edit' ? $this->editLocationSpaces : $this->createLocationSpaces;
            @endphp
            <form wire:submit="saveForm">
                <div class="grid grid-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" wire:model="{{ $formMode === 'edit' ? 'editName' : 'createName' }}" class="form-input" placeholder="Nama lengkap user">
                        @error($formMode === 'edit' ? 'editName' : 'createName') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" wire:model="{{ $formMode === 'edit' ? 'editEmail' : 'createEmail' }}" class="form-input" placeholder="email@polman.ac.id">
                        @error($formMode === 'edit' ? 'editEmail' : 'createEmail') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" wire:model="{{ $formMode === 'edit' ? 'editPassword' : 'createPassword' }}" class="form-input" placeholder="{{ $formMode === 'edit' ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}">
                        @error($formMode === 'edit' ? 'editPassword' : 'createPassword') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Tipe User</label>
                        <select wire:model="{{ $formMode === 'edit' ? 'editUserType' : 'createUserType' }}" class="form-select">
                            <option value="umum">Umum</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                        </select>
                        @error($formMode === 'edit' ? 'editUserType' : 'createUserType') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select id="{{ $formMode === 'edit' ? 'editRoleId' : 'createRoleId' }}" wire:model="{{ $formMode === 'edit' ? 'editRole' : 'createRole' }}" class="form-select" wire:change="$refresh">
                            <option value="reporter">Pelapor</option>
                            <option value="admin">Admin</option>
                            <option value="pimpinan">Pimpinan</option>
                            <option value="spmi">SPMI / Auditor</option>
                            <option value="pj_area">PJ Area</option>
                        </select>
                        @error($formMode === 'edit' ? 'editRole' : 'createRole') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($currentRole === 'reporter')
                <div class="form-group">
                    <label class="form-label flex items-center gap-3">
                        <input type="checkbox" wire:model="{{ $formMode === 'edit' ? 'editShowNameOnLanding' : 'createShowNameOnLanding' }}" class="form-checkbox">
                        <span>Tampilkan nama di landing page</span>
                    </label>
                    <p class="form-help">Jika dimatikan, pelapor akan tampil sebagai "Anonim" pada leaderboard publik.</p>
                </div>
                @endif

                @if(in_array($currentRole, ['pj_area', 'pimpinan', 'spmi']))
                <div wire:key="user-location-groups-{{ $formMode }}-{{ $currentLocationCampusId }}">
                    <div class="grid grid-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Pilih Kampus</label>
                            <select id="{{ $formMode === 'edit' ? 'editLocationCampusId' : 'createLocationCampusId' }}" wire:model="{{ $formMode === 'edit' ? 'editLocationCampusId' : 'createLocationCampusId' }}" wire:change="$refresh" class="form-select">
                                <option value="">Semua Area (akses seluruh area)</option>
                                @foreach($currentLocationCampuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->label }}</option>
                                @endforeach
                            </select>
                            @error($formMode === 'edit' ? 'editLocationCampusId' : 'createLocationCampusId') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pilih Gedung / Infrastruktur</label>
                            <select id="{{ $formMode === 'edit' ? 'editLocationBranchId' : 'createLocationBranchId' }}" wire:model="{{ $formMode === 'edit' ? 'editLocationBranchId' : 'createLocationBranchId' }}" wire:change="$refresh" class="form-select" @disabled(empty($currentLocationCampusId))>
                                <option value="">Semua Gedung</option>
                                @foreach($currentLocationBranches as $branch)
                                    <option value="{{ $branch->id }}" data-type="{{ $branch->type ?? '' }}">{{ $branch->label }}</option>
                                @endforeach
                            </select>
                            @error($formMode === 'edit' ? 'editLocationBranchId' : 'createLocationBranchId') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @if(($currentLocationBranchType ?? '') === 'gedung')
                    <div wire:key="user-location-floor-space-{{ $formMode }}-{{ $currentLocationBranchId }}-{{ $currentLocationFloorId }}" class="grid grid-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Lantai <span class="text-danger">*</span></label>
                            <select id="{{ $formMode === 'edit' ? 'editLocationFloorId' : 'createLocationFloorId' }}" wire:model="{{ $formMode === 'edit' ? 'editLocationFloorId' : 'createLocationFloorId' }}" wire:change="$refresh" class="form-select">
                                <option value="all">Semua Lantai</option>
                                @foreach($currentLocationFloors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->label }}</option>
                                @endforeach
                            </select>
                            @error($formMode === 'edit' ? 'editLocationFloorId' : 'createLocationFloorId') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pilih Ruangan</label>
                            <select id="{{ $formMode === 'edit' ? 'editLocationSpaceId' : 'createLocationSpaceId' }}" wire:model="{{ $formMode === 'edit' ? 'editLocationSpaceId' : 'createLocationSpaceId' }}" class="form-select">
                                <option value="">Semua Ruangan</option>
                                @foreach($currentLocationSpaces as $space)
                                    <option value="{{ $space->id }}">{{ $space->label }}</option>
                                @endforeach
                            </select>
                            @error($formMode === 'edit' ? 'editLocationSpaceId' : 'createLocationSpaceId') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" wire:click="closeForm" class="btn btn-outline">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tipe</th>
                            <th>ID</th>
                            <th>Area</th>
                            <th>Role</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="font-medium">{{ $user->full_name }}</td>
                            <td class="text-sm text-muted">{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->user_type === 'mahasiswa' ? 'badge-info' : ($user->user_type === 'dosen' ? 'badge-warning' : 'badge-neutral') }}">
                                    {{ $user->user_type_label }}
                                </span>
                            </td>
                            <td class="text-sm">
                                @if($user->isMahasiswa()) {{ $user->nim }}
                                @elseif($user->isDosen()) {{ $user->nomor_dosen }}
                                @else -
                                @endif
                            </td>
                            <td class="text-sm">
                                @if($user->isPjArea() || in_array($user->role, ['pimpinan', 'spmi'], true))
                                    {{ $user->assigned_location_labels ?: 'Semua Area' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'badge-danger' : ($user->role === 'pimpinan' ? 'badge-info' : ($user->role === 'spmi' ? 'badge-secondary' : ($user->role === 'pj_area' ? 'badge-success' : 'badge-primary'))) }}">
                                    {{ $user->role === 'reporter' ? 'Pelapor' : ($user->role === 'pimpinan' ? 'Pimpinan' : ($user->role === 'spmi' ? 'SPMI' : ($user->role === 'pj_area' ? 'PJ Area' : ucfirst($user->role)))) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button wire:click="openEdit({{ $user->id }})" class="btn btn-outline btn-sm">
                                    <i data-lucide="pencil" style="width:14px;height:14px;"></i> Edit
                                </button>
                                <button wire:click="deleteUser({{ $user->id }})" onclick="return confirm('Yakin hapus user ini?')" class="btn btn-danger btn-sm ml-2">
                                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:40px;">
                                <p class="text-muted">Belum ada user.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer">{{ $users->links() }}</div>
        @endif
    </div>

</div>
