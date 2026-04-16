<div>
    <div class="page-header">
        <h1>Kelola Peringatan</h1>
        <p>Kelola peringatan bahaya dan situasi penting untuk ditampilkan kepada pengguna</p>
    </div>

    {{-- Message Alert --}}
    @if($message)
    <div class="alert alert-{{ $messageType }} alert-dismissible animate-in mb-6" role="alert">
        <div class="flex items-center gap-3">
            @if($messageType === 'success')
                <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
            @else
                <i data-lucide="alert-circle" style="width:20px;height:20px;"></i>
            @endif
            <span>{{ $message }}</span>
        </div>
        <button type="button" wire:click="$set('message', null)" class="btn-close" aria-label="Close"></button>
    </div>
    @endif

    {{-- Search & Filters --}}
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex gap-3 items-end flex-wrap">
                <div class="flex-1" style="min-width:250px;">
                    <label class="form-label">Cari Peringatan</label>
                    <input type="text" wire:model.live="search" class="form-input" placeholder="Cari judul atau deskripsi...">
                </div>
                <div>
                    <label class="form-label">Keparahan</label>
                    <select wire:model.live="filterSeverity" class="form-input">
                        <option value="all">Semua</option>
                        <option value="low">Rendah</option>
                        <option value="medium">Sedang</option>
                        <option value="high">Tinggi</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select wire:model.live="filterStatus" class="form-input">
                        <option value="all">Semua</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <button wire:click="openForm" class="btn btn-primary btn-sm">
                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Tambah Peringatan
                </button>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    @if($showForm)
    <div class="card mb-6 animate-in">
        <div class="card-header">
            <h3>{{ $editingId ? 'Edit' : 'Tambah' }} Peringatan</h3>
        </div>
        <div class="card-body">
            <form wire:submit="save" class="animate-in">
                <div class="grid grid-2 gap-4 mb-4">
                    <div class="form-group">
                        <label class="form-label">Judul Peringatan</label>
                        <input type="text" wire:model="formTitle" class="form-input" placeholder="Contoh: Kebakaran di Lab">
                        @error('formTitle') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keparahan</label>
                        <select wire:model="formSeverity" class="form-input">
                            <option value="low">Rendah</option>
                            <option value="medium" selected>Sedang</option>
                            <option value="high">Tinggi</option>
                        </select>
                        @error('formSeverity') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea wire:model="formDescription" class="form-input" rows="4" placeholder="Jelaskan peringatan secara detail..."></textarea>
                    @error('formDescription') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-2 gap-4 mb-4">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select wire:model="formStatus" class="form-input">
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                            <option value="expired">Expired</option>
                        </select>
                        @error('formStatus') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Berakhir (Opsional)</label>
                        <input type="date" wire:model="formExpiresAt" class="form-input">
                        @error('formExpiresAt') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-2 gap-4 mb-4">
                    <div class="form-group">
                        <label class="form-label">Laporan Terkait (Opsional)</label>
                        <select wire:model="formReportId" class="form-input">
                            <option value="">-- Pilih Laporan --</option>
                            @foreach($approvedReports as $report)
                            <option value="{{ $report->id }}">{{ $report->code }} - {{ $report->deskripsi }}</option>
                            @endforeach
                        </select>
                        @error('formReportId') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group flex items-end gap-2">
                        <label class="form-checkbox">
                            <input type="checkbox" wire:model="formIsPublic">
                            <span>Tampilkan di Landing Page</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="save" style="width:14px;height:14px;"></i>
                        {{ $editingId ? 'Perbarui' : 'Simpan' }}
                    </button>
                    <button type="button" wire:click="closeForm" class="btn btn-outline btn-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Warnings Table --}}
    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th style="width:100px;">Keparahan</th>
                            <th style="width:100px;">Status</th>
                            <th style="width:130px;">Berlaku Sampai</th>
                            <th style="width:60px;">Publik</th>
                            <th style="width:160px;" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warnings as $warning)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $warning->title }}</div>
                                <div class="text-sm text-muted">{{ Str::limit($warning->description, 50) }}</div>
                            </td>
                            <td>
                                <span class="badge {{ match($warning->severity) {
                                    'low' => 'badge-info',
                                    'medium' => 'badge-warning',
                                    'high' => 'badge-danger',
                                } }}">
                                    {{ $warning->severity_label }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ match($warning->status) {
                                    'active' => 'badge-success',
                                    'inactive' => 'badge-neutral',
                                    'expired' => 'badge-secondary',
                                } }}">
                                    {{ $warning->status_label }}
                                </span>
                            </td>
                            <td class="text-sm text-muted">
                                @if($warning->expires_at)
                                    {{ $warning->expires_at->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                @if($warning->is_public)
                                    <span class="badge badge-success">Ya</span>
                                @else
                                    <span class="badge badge-neutral">Tidak</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button wire:click="openForm({{ $warning->id }})" class="btn btn-outline btn-sm" title="Edit">
                                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                </button>
                                <button wire:click="toggleStatus({{ $warning->id }})" class="btn btn-outline btn-sm" title="Toggle Status">
                                    <i data-lucide="{{ $warning->status === 'active' ? 'pause' : 'play' }}" style="width:13px;height:13px;"></i>
                                </button>
                                <button wire:click="deleteWarning({{ $warning->id }})" onclick="return confirm('Yakin hapus peringatan ini?')" class="btn btn-danger btn-sm" title="Hapus">
                                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i data-lucide="alert-circle" style="width:40px;height:40px;"></i>
                                    <p>Belum ada peringatan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($warnings->hasPages())
            <div style="padding: 16px; border-top: 1px solid var(--border-light); display: flex; justify-content: center;">
                {{ $warnings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
