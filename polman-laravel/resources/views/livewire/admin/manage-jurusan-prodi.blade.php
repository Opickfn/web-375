<div>
    <div class="page-header">
        <h1>Kelola Jurusan & Program Studi</h1>
        <p>Atur data jurusan dan program studi yang tersedia di sistem</p>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         JURUSAN SECTION
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="card mb-6">
        <div class="card-header">
            <h3>Daftar Jurusan</h3>
            <button wire:click="openJurusanForm" class="btn btn-primary btn-sm">
                <i data-lucide="plus" style="width:14px;height:14px;"></i> Tambah Jurusan
            </button>
        </div>

        @if($showJurusanForm)
        <div class="card-body" style="border-bottom:1px solid var(--border);">
            <form wire:submit="saveJurusan" class="animate-in">
                <div class="grid grid-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Kode Jurusan</label>
                        <input type="text" wire:model="jKode" class="form-input" placeholder="Contoh: TOMM" maxlength="10" style="text-transform:uppercase;">
                        @error('jKode') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Jurusan (Indonesia)</label>
                        <input type="text" wire:model="jNama" class="form-input" placeholder="Contoh: Teknik Otomasi Manufaktur dan Mekatronika">
                        @error('jNama') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Jurusan (English) <span class="text-muted">opsional</span></label>
                        <input type="text" wire:model="jNamaEn" class="form-input" placeholder="Contoh: Automation Engineering">
                        @error('jNamaEn') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex gap-3 mt-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="save" style="width:14px;height:14px;"></i>
                        {{ $editJurusanId ? 'Perbarui' : 'Simpan' }}
                    </button>
                    <button type="button" wire:click="closeJurusanForm" class="btn btn-outline btn-sm">Batal</button>
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
                            <th>Nama Jurusan</th>
                            <th>Nama (EN)</th>
                            <th style="width:80px;">Prodi</th>
                            <th style="width:80px;">Status</th>
                            <th style="width:140px;" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurusans as $j)
                        <tr class="{{ !$j->is_active ? 'opacity-50' : '' }}">
                            <td class="font-medium">{{ $j->kode }}</td>
                            <td>{{ $j->nama }}</td>
                            <td class="text-sm text-muted">{{ $j->nama_en ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $j->program_studis_count }}</span></td>
                            <td>
                                <span class="badge {{ $j->is_active ? 'badge-success' : 'badge-neutral' }}">
                                    {{ $j->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button wire:click="openJurusanForm({{ $j->id }})" class="btn btn-outline btn-sm">
                                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                </button>
                                <button wire:click="toggleJurusan({{ $j->id }})" class="btn {{ $j->is_active ? 'btn-outline' : 'btn-success' }} btn-sm" title="{{ $j->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i data-lucide="{{ $j->is_active ? 'eye-off' : 'eye' }}" style="width:13px;height:13px;"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Inline prodi list --}}
                        @if($j->programStudis->count())
                        <tr>
                            <td colspan="6" style="padding:0;background:var(--bg-secondary);">
                                <div style="padding:8px 16px 8px 48px;">
                                    @foreach($j->programStudis as $p)
                                    <div style="display:flex;align-items:center;gap:8px;padding:4px 0;{{ !$p->is_active ? 'opacity:.5;' : '' }}">
                                        <span class="badge badge-neutral" style="font-size:.7rem;">{{ $p->kode }}</span>
                                        <span class="text-sm">{{ $p->jenjang }} {{ $p->nama }}</span>
                                        <span class="badge {{ $p->is_active ? 'badge-success' : 'badge-neutral' }}" style="font-size:.65rem;margin-left:auto;">{{ $p->is_active ? 'Aktif' : 'Off' }}</span>
                                        <button wire:click="openProdiForm({{ $p->id }})" class="btn btn-outline btn-sm" style="padding:2px 6px;">
                                            <i data-lucide="pencil" style="width:12px;height:12px;"></i>
                                        </button>
                                        <button wire:click="toggleProdi({{ $p->id }})" class="btn btn-outline btn-sm" style="padding:2px 6px;">
                                            <i data-lucide="{{ $p->is_active ? 'eye-off' : 'eye' }}" style="width:12px;height:12px;"></i>
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
                                    <i data-lucide="graduation-cap" style="width:40px;height:40px;"></i>
                                    <p>Belum ada data jurusan.</p>
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
         PROGRAM STUDI FORM (Floating)
         ═══════════════════════════════════════════════════════════════ --}}
    @if($showProdiForm)
    <div class="card mb-6 animate-in">
        <div class="card-header">
            <h3>{{ $editProdiId ? 'Edit' : 'Tambah' }} Program Studi</h3>
        </div>
        <div class="card-body">
            <form wire:submit="saveProdi">
                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Jurusan Induk</label>
                        <select wire:model="pJurusanId" class="form-select">
                            <option value="">Pilih jurusan</option>
                            @foreach($allJurusans as $aj)
                                <option value="{{ $aj->id }}">{{ $aj->kode }} - {{ $aj->nama }}</option>
                            @endforeach
                        </select>
                        @error('pJurusanId') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kode Prodi</label>
                        <input type="text" wire:model="pKode" class="form-input" placeholder="Contoh: TOM" maxlength="10" style="text-transform:uppercase;">
                        @error('pKode') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nama Program Studi</label>
                        <input type="text" wire:model="pNama" class="form-input" placeholder="Contoh: Teknik Otomasi Manufaktur">
                        @error('pNama') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenjang</label>
                        <select wire:model="pJenjang" class="form-select">
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
                        {{ $editProdiId ? 'Perbarui' : 'Simpan' }}
                    </button>
                    <button type="button" wire:click="closeProdiForm" class="btn btn-outline btn-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div style="text-align:center;padding:12px 0;">
        <button wire:click="openProdiForm" class="btn btn-outline">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Program Studi Baru
        </button>
    </div>
    @endif
</div>
