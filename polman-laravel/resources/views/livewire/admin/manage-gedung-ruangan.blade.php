<div>
    <div class="page-header">
        <h1>Kelola Gedung & Ruangan</h1>
        <p>Atur data gedung dan ruangan yang tersedia di sistem</p>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         GEDUNG SECTION
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="card mb-6">
        <div class="card-header">
            <h3>Daftar Gedung</h3>
            <button wire:click="openGedungForm" class="btn btn-primary btn-sm">
                <i data-lucide="plus" style="width:14px;height:14px;"></i> Tambah Gedung
            </button>
        </div>

        @if($showGedungForm)
        <div class="card-body" style="border-bottom:1px solid var(--border);">
            <form wire:submit="saveGedung" class="animate-in">
                <div class="grid grid-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Kode Gedung</label>
                        <input type="text" wire:model="gKode" class="form-input" placeholder="Contoh: A" maxlength="10" style="text-transform:uppercase;">
                        @error('gKode') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Gedung (Indonesia)</label>
                        <input type="text" wire:model="gNama" class="form-input" placeholder="Contoh: Gedung Utama">
                        @error('gNama') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Gedung (English) <span class="text-muted">opsional</span></label>
                        <input type="text" wire:model="gNamaEn" class="form-input" placeholder="Contoh: Main Building">
                        @error('gNamaEn') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex gap-3 mt-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="save" style="width:14px;height:14px;"></i>
                        {{ $editGedungId ? 'Perbarui' : 'Simpan' }}
                    </button>
                    <button type="button" wire:click="closeGedungForm" class="btn btn-outline btn-sm">Batal</button>
                </div>
            </form>
        </div>
        @endif

        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:80px;">Kode</th>
                            <th>Nama Gedung</th>
                            <th>Nama (EN)</th>
                            <th style="width:100px;">Ruangan</th>
                            <th style="width:80px;">Status</th>
                            <th style="width:140px;" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gedungs as $g)
                        <tr class="{{ !$g->is_active ? 'opacity-50' : '' }}">
                            <td class="font-medium">{{ $g->kode }}</td>
                            <td>{{ $g->nama }}</td>
                            <td class="text-sm text-muted">{{ $g->nama_en ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $g->ruangans_count }}</span></td>
                            <td>
                                <span class="badge {{ $g->is_active ? 'badge-success' : 'badge-neutral' }}">
                                    {{ $g->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button wire:click="openGedungForm({{ $g->id }})" class="btn btn-outline btn-sm">
                                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                </button>
                                <button wire:click="toggleGedung({{ $g->id }})" class="btn {{ $g->is_active ? 'btn-outline' : 'btn-success' }} btn-sm" title="{{ $g->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i data-lucide="{{ $g->is_active ? 'eye-off' : 'eye' }}" style="width:13px;height:13px;"></i>
                                </button>
                                <button wire:click="deleteGedung({{ $g->id }})" wire:confirm="Yakin hapus gedung ini? Tindakan tidak dapat dibatalkan." class="btn btn-danger btn-sm" title="Hapus">
                                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Inline ruangan list --}}
                        @if($g->ruangans->count())
                        <tr>
                            <td colspan="6" style="padding:0;background:var(--bg-secondary);">
                                <div style="padding:8px 16px 8px 48px;">
                                    @foreach($g->ruangans as $r)
                                    <div style="display:flex;align-items:center;gap:8px;padding:4px 0;{{ !$r->is_active ? 'opacity:.5;' : '' }}">
                                        <span class="badge badge-neutral" style="font-size:.7rem;">{{ $r->kode }}</span>
                                        <span class="text-sm">{{ $r->jenjang }} {{ $r->nama }}</span>
                                        <span class="badge {{ $r->is_active ? 'badge-success' : 'badge-neutral' }}" style="font-size:.65rem;margin-left:auto;">{{ $r->is_active ? 'Aktif' : 'Off' }}</span>
                                        <button wire:click="openRuanganForm({{ $r->id }})" class="btn btn-outline btn-sm" style="padding:2px 6px;">
                                            <i data-lucide="pencil" style="width:12px;height:12px;"></i>
                                        </button>
                                        <button wire:click="toggleRuangan({{ $r->id }})" class="btn btn-outline btn-sm" style="padding:2px 6px;">
                                            <i data-lucide="{{ $r->is_active ? 'eye-off' : 'eye' }}" style="width:12px;height:12px;"></i>
                                        </button>
                                        <button wire:click="deleteRuangan({{ $r->id }})" wire:confirm="Yakin hapus ruangan ini? Tindakan tidak dapat dibatalkan." class="btn btn-danger btn-sm" style="padding:2px 6px;">
                                            <i data-lucide="trash-2" style="width:12px;height:12px;"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i data-lucide="building" style="width:40px;height:40px;"></i>
                                    <p>Belum ada data gedung.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         RUANGAN FORM (Floating)
         ═══════════════════════════════════════════════════════════════ --}}
    @if($showRuanganForm)
    <div class="card mb-6 animate-in">
        <div class="card-header">
            <h3>{{ $editRuanganId ? 'Edit' : 'Tambah' }} Ruangan</h3>
        </div>
        <div class="card-body">
            <form wire:submit="saveRuangan">
                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Gedung Induk</label>
                        <select wire:model="rGedungId" class="form-select">
                            <option value="">Pilih gedung</option>
                            @foreach($allGedungs as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->kode }} - {{ $ag->nama }}</option>
                            @endforeach
                        </select>
                        @error('rGedungId') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kode Ruangan</label>
                        <input type="text" wire:model="rKode" class="form-input" placeholder="Contoh: 101" maxlength="10" style="text-transform:uppercase;">
                        @error('rKode') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" wire:model="rNama" class="form-input" placeholder="Contoh: Ruang Kelas A">
                        @error('rNama') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenjang</label>
                        <select wire:model="rJenjang" class="form-select">
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="D4">D4</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="save" style="width:14px;height:14px;"></i>
                        {{ $editRuanganId ? 'Perbarui' : 'Simpan' }}
                    </button>
                    <button type="button" wire:click="closeRuanganForm" class="btn btn-outline btn-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div style="text-align:center;padding:12px 0;">
        <button wire:click="openRuanganForm" class="btn btn-outline">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Ruangan Baru
        </button>
    </div>
    @endif
</div>
