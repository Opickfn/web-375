<div>
    <div class="page-header">
        <h1>Kelola User</h1>
        <p>Daftar semua user dan pengaturan role</p>
    </div>

    <div class="card mb-4">
        <div class="card-body flex gap-3 flex-wrap items-center">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-input" placeholder="Cari nama, email, atau NIM..." style="max-width:280px;">
            <select wire:model.live="filterRole" class="form-select" style="max-width:160px;">
                <option value="">Semua Role</option>
                <option value="reporter">Reporter</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
        </div>
    </div>

    @if($showEdit)
    <div class="card mb-4 animate-in">
        <div class="card-header"><h3>Edit User</h3></div>
        <div class="card-body">
            <form wire:submit="saveEdit">
                <div class="grid grid-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input type="text" wire:model="editName" class="form-input">
                        @error('editName') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select wire:model="editRole" class="form-select">
                            <option value="reporter">Reporter</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe User</label>
                        <select wire:model="editUserType" class="form-select" disabled>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" wire:click="closeEdit" class="btn btn-outline">Batal</button>
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
                            <th>Jurusan / Jabatan</th>
                            <th>Role</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="font-medium">{{ $user->full_name }}</td>
                            <td class="text-sm text-muted">{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ match($user->user_type) { 'mahasiswa' => 'badge-info', 'dosen' => 'badge-warning', default => 'badge-neutral' } }}">
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
                                @if($user->isMahasiswa()) {{ $user->jurusan }}
                                @elseif($user->isDosen()) {{ $user->jabatan }}
                                @else -
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ match($user->role) { 'admin' => 'badge-danger', 'manager' => 'badge-warning', default => 'badge-primary' } }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button wire:click="openEdit({{ $user->id }})" class="btn btn-outline btn-sm">
                                    <i data-lucide="pencil" style="width:14px;height:14px;"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer">{{ $users->links() }}</div>
        @endif
    </div>
</div>
