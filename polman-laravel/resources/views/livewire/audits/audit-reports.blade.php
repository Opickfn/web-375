<div x-data="auditAnim()" x-init="init()">
    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Audit PJ Area</h1>
            <p class="pm-sub">Dokumentasi dan rekap laporan audit area</p>
        </div>
        <div style="display:flex;gap:0.6rem;">
            <button wire:click="exportAudit" class="pm-btn pm-btn-ghost">
                <i data-lucide="download" style="width:15px;height:15px;"></i> Export
            </button>
            <button wire:click="openForm" class="pm-btn pm-btn-primary">
                <i data-lucide="file-plus" style="width:15px;height:15px;"></i> Buat Audit
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="pm-flash pm-flash-success">
        <i data-lucide="check-circle" style="width:15px;height:15px;"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($showForm)
    <div class="pm-card" style="margin-bottom:1.25rem;">
        <div class="pm-card-header">
            <h3>{{ $editId ? 'Edit' : 'Buat' }} Laporan Audit</h3>
            <button wire:click="closeForm" class="pm-btn pm-btn-ghost pm-btn-icon">
                <i data-lucide="x" style="width:14px;height:14px;"></i>
            </button>
        </div>
        <div class="pm-card-body">
            <form wire:submit="save" enctype="multipart/form-data">
                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Target Audit</label>
                        <select wire:model="auditTarget" wire:change="$refresh" class="pm-select-full">
                            <option value="pj_area">PJ Area</option>
                            <option value="lokasi">Lokasi</option>
                        </select>
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Tanggal Audit</label>
                        <input type="date" wire:model="auditDate" class="pm-input">
                        @error('auditDate') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($auditTarget === 'pj_area')
                <div class="pm-form-group">
                    <label class="pm-label">PJ Area</label>
                    <select wire:model="pjAreaId" wire:change="$refresh" class="pm-select-full">
                        <option value="">Pilih PJ Area...</option>
                        @foreach($pjAreas as $p)
                        <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                        @endforeach
                    </select>
                    @error('pjAreaId') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                @endif

                <div class="pm-form-group">
                    <label class="pm-label">Lokasi</label>
                    <select wire:key="ab-{{ $pjAreaId }}" wire:model="branchId" wire:change="$refresh" class="pm-select-full">
                        <option value="">Pilih Lokasi...</option>
                        @foreach($this->assignedLocations as $l)
                        <option value="{{ $l->id }}">{{ $l->label }}</option>
                        @endforeach
                    </select>
                    @error('branchId') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>

                @if(in_array($this->branchType, ['gedung', 'lantai']))
                <div class="pm-grid-2" style="gap:1rem;">
                    @if($this->branchType === 'gedung')
                    <div class="pm-form-group">
                        <label class="pm-label">Lantai</label>
                        <select wire:model="floorId" wire:change="$refresh" class="pm-select-full">
                            <option value="">Pilih...</option>
                            @foreach($this->floors as $f)
                            <option value="{{ $f->id }}">{{ $f->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="pm-form-group">
                        <label class="pm-label">Ruangan / Area</label>
                        <select wire:model="spaceId" class="pm-select-full">
                            <option value="">Pilih...</option>
                            @foreach($this->spaces as $s)
                            <option value="{{ $s->id }}">{{ $s->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="pm-form-group">
                    <label class="pm-label">Judul Audit</label>
                    <input type="text" wire:model="title" class="pm-input" placeholder="Judul laporan audit">
                    @error('title') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                <div class="pm-form-group">
                    <label class="pm-label">Temuan</label>
                    <textarea wire:model="findings" class="pm-textarea" placeholder="Deskripsi temuan..."></textarea>
                    @error('findings') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                <div class="pm-form-group">
                    <label class="pm-label">Rekomendasi</label>
                    <textarea wire:model="recommendations" class="pm-textarea" style="min-height:80px;" placeholder="Rekomendasi perbaikan..."></textarea>
                </div>
                <div class="pm-form-group">
                    <label class="pm-label">Upload PDF</label>
                    <input type="file" wire:model="pdfFile" accept="application/pdf" class="pm-input">
                    @error('pdfFile') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                <div style="display:flex;gap:0.6rem;">
                    <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled">
                        {{ $editId ? 'Perbarui' : 'Simpan' }}
                    </button>
                    <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="pm-card">
        <div class="pm-table-wrap">
            <table class="pm-table" id="audit-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>PJ Area</th>
                        <th>Lokasi</th>
                        <th>Auditor</th>
                        <th>Tanggal</th>
                        <th>PDF</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditReports as $a)
                    <tr>
                        <td>{{ $a->title }}</td>
                        <td>{{ $a->pjArea->full_name ?? '—' }}</td>
                        <td>{{ $a->location->full_path ?? '—' }}</td>
                        <td>{{ $a->auditor->full_name ?? '—' }}</td>
                        <td>{{ $a->audit_date->format('d M Y') }}</td>
                        <td>
                            @if($a->pdf_path)
                            <a href="{{ route('audits.download', $a) }}" class="pm-btn pm-btn-outline pm-btn-sm">
                                PDF
                            </a>
                            @else
                            —
                            @endif
                        </td>
                        <td>
                            <span class="pm-badge {{ $a->status === 'closed' ? 'pm-badge-success' : 'pm-badge-info' }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="openEdit({{ $a->id }})" class="pm-btn pm-btn-outline pm-btn-sm">
                                Edit
                            </button>
                            <button wire:click="deleteReport({{ $a->id }})" onclick="return confirm('Hapus?')" class="pm-btn pm-btn-danger pm-btn-sm">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center p-8">Belum ada laporan audit</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $auditReports->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function auditAnim() {
    return {
        init() {},
        ar() {}
    };
}
</script>
@endpush>

