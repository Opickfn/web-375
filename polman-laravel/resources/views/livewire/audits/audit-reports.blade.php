<div x-data="auditAnim()" x-init="init()" class="audit-container">
    <div class="pm-page-header pm-fade-in">
        <div class="header-content">
            <h1 class="pm-h1 flex items-center gap-3">
                <i data-lucide="shield-check" class="text-[#5588A3]"></i>
                Audit PJ Area
            </h1>
            <p class="pm-sub">Dokumentasi strategis dan rekapitulasi laporan audit area industri</p>
        </div>
        <div class="header-actions">
            <button wire:click="exportAudit" class="pm-btn pm-btn-outline glow-blue">
                <i data-lucide="download"></i> <span>Export PDF</span>
            </button>
            
            @if(in_array(Auth::user()->role, ['admin', 'spmi']))
            <button wire:click="openForm" class="pm-btn pm-btn-primary glow-orange">
                <i data-lucide="plus-circle"></i> <span>Buat Audit Baru</span>
            </button>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="pm-flash pm-flash-success animate-in">
        <i data-lucide="check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($showForm)
    <div class="pm-card glass-form animate-in" style="margin-bottom:2rem;">
        <div class="pm-card-header border-b border-white/5">
            <h3 class="text-lg font-bold text-[#5588A3] flex items-center gap-2">
                <i data-lucide="{{ $editId ? 'edit' : 'plus-square' }}" class="w-5 h-5"></i>
                {{ $editId ? 'Perbarui' : 'Inisiasi' }} Laporan Audit
            </h3>
            <button wire:click="closeForm" class="pm-btn-icon-close">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="pm-card-body p-6">
            <form wire:submit="save" enctype="multipart/form-data" class="space-y-6">
                <div class="grid lg:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="pm-label">Target Audit</label>
                        <div class="input-wrapper">
                            <select wire:model="auditTarget" wire:change="$refresh" class="pm-select-modern">
                                <option value="pj_area">Berdasarkan PJ Area</option>
                                <option value="lokasi">Berdasarkan Lokasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="pm-label">Tanggal Audit</label>
                        <div class="input-wrapper">
                            <input type="date" wire:model="auditDate" class="pm-input-modern">
                        </div>
                        @error('auditDate') <p class="pm-error-msg">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-6">
                    @if($auditTarget === 'pj_area')
                    <div class="form-group">
                        <label class="pm-label">PJ Area Bertanggung Jawab</label>
                        <div class="input-wrapper">
                            <select wire:model="pjAreaId" wire:change="$refresh" class="pm-select-modern">
                                <option value="">Pilih PJ Area...</option>
                                @foreach($pjAreas as $p)
                                <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('pjAreaId') <p class="pm-error-msg">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="pm-label">Lokasi Utama</label>
                        <div class="input-wrapper">
                            <select wire:key="ab-{{ $pjAreaId }}" wire:model="branchId" wire:change="$refresh" class="pm-select-modern">
                                <option value="">Pilih Lokasi...</option>
                                @foreach($this->assignedLocations as $l)
                                <option value="{{ $l->id }}">{{ $l->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('branchId') <p class="pm-error-msg">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if(in_array($this->branchType, ['gedung', 'lantai']))
                <div class="grid lg:grid-cols-2 gap-6">
                    @if($this->branchType === 'gedung')
                    <div class="form-group">
                        <label class="pm-label">Lantai</label>
                        <div class="input-wrapper">
                            <select wire:model="floorId" wire:change="$refresh" class="pm-select-modern">
                                <option value="">Pilih Lantai...</option>
                                @foreach($this->floors as $f)
                                <option value="{{ $f->id }}">{{ $f->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="pm-label">Ruangan / Spesifik Area</label>
                        <div class="input-wrapper">
                            <select wire:model="spaceId" class="pm-select-modern">
                                <option value="">Pilih Ruang...</option>
                                @foreach($this->spaces as $s)
                                <option value="{{ $s->id }}">{{ $s->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label class="pm-label">Judul Laporan Audit</label>
                    <div class="input-wrapper">
                        <input type="text" wire:model="title" class="pm-input-modern" placeholder="Contoh: Audit K3 Workshop Pemesinan Semester 1">
                    </div>
                    @error('title') <p class="pm-error-msg">{{ $message }}</p> @enderror
                </div>

                <div class="grid lg:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="pm-label">Temuan Utama</label>
                        <textarea wire:model="findings" class="pm-textarea-modern" placeholder="Deskripsikan temuan lapangan secara detail..."></textarea>
                        @error('findings') <p class="pm-error-msg">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="pm-label">Rekomendasi Tindakan</label>
                        <textarea wire:model="recommendations" class="pm-textarea-modern" placeholder="Berikan saran atau rekomendasi perbaikan..."></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="pm-label">Dokumen Audit (PDF)</label>
                    <div class="upload-zone">
                        <input type="file" wire:model="pdfFile" accept="application/pdf" class="hidden" id="pdf-upload">
                        <label for="pdf-upload" class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-[#5588A3] transition-all bg-slate-50/50">
                            <i data-lucide="file-text" class="w-10 h-10 text-slate-400 mb-2"></i>
                            <span class="text-sm font-medium text-slate-600">Klik untuk upload file PDF audit</span>
                            <span class="text-[10px] text-slate-400 mt-1">Maksimal 10MB</span>
                        </label>
                    </div>
                    @error('pdfFile') <p class="pm-error-msg">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-white/5">
                    <button type="submit" class="pm-btn pm-btn-primary px-8 h-[45px] glow-orange" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editId ? 'Update Laporan' : 'Simpan Laporan' }}</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                    <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost h-[45px]">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="pm-card glass-table-card pm-fade-in">
        <div class="pm-table-wrap">
            <table class="pm-table-modern" id="audit-table">
                <thead>
                    <tr>
                        <th class="rounded-tl-2xl">Judul Audit</th>
                        <th>PJ Area</th>
                        <th>Lokasi</th>
                        <th>Auditor</th>
                        <th>Tanggal</th>
                        <th>File</th>
                        <th>Status</th>
                        <th class="rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditReports as $a)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="font-bold text-[#00334E]">{{ $a->title }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#5588A3]/10 flex items-center justify-center text-[10px] font-bold text-[#5588A3]">
                                    {{ substr($a->pjArea->full_name ?? '—', 0, 1) }}
                                </div>
                                <span class="text-sm">{{ $a->pjArea->full_name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="text-sm text-slate-500 max-w-[200px] truncate">{{ $a->location->full_path ?? '—' }}</td>
                        <td class="text-sm font-medium">{{ $a->auditor->full_name ?? '—' }}</td>
                        <td class="text-sm text-slate-500">{{ $a->audit_date->format('d M Y') }}</td>
                        <td>
                            @if($a->pdf_path)
                            <a href="{{ route('audits.download', $a) }}" class="file-badge">
                                <i data-lucide="file-down" class="w-3.5 h-3.5"></i> PDF
                            </a>
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="pm-badge {{ $a->status === 'closed' ? 'pm-badge-success' : 'pm-badge-info' }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                @if(in_array(Auth::user()->role, ['admin', 'spmi']))
                                    <button wire:click="openEdit({{ $a->id }})" class="action-btn action-btn-edit" title="Edit">
                                        <i data-lucide="edit-2"></i>
                                    </button>
                                    <button wire:click="deleteReport({{ $a->id }})" onclick="return confirm('Hapus laporan audit ini?')" class="action-btn action-btn-delete" title="Hapus">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                @else
                                    <span class="text-[10px] uppercase font-bold text-slate-300">Read Only</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="flex flex-col items-center py-12 text-slate-400">
                                <i data-lucide="inbox" class="w-12 h-12 mb-3 opacity-20"></i>
                                <p class="font-medium text-sm">Belum ada laporan audit yang tercatat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
            {{ $auditReports->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
    .audit-container {
        padding-bottom: 2rem;
    }
    .pm-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .pm-h1 {
        font-size: 1.875rem;
        font-weight: 800;
        color: #00334E;
        letter-spacing: -0.025em;
    }
    .pm-sub {
        color: #64748b;
        font-size: 0.9375rem;
    }
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    /* Card & Forms */
    .glass-form {
        background: white;
        border: 1px solid rgba(0, 51, 78, 0.05);
        box-shadow: 0 10px 40px -10px rgba(0, 51, 78, 0.1);
        border-radius: 1.5rem;
    }
    .pm-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
        display: block;
    }
    .pm-input-modern, .pm-select-modern, .pm-textarea-modern {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: #1e293b;
        transition: all 0.3s ease;
    }
    .pm-input-modern:focus, .pm-select-modern:focus, .pm-textarea-modern:focus {
        border-color: #5588A3;
        background: white;
        box-shadow: 0 0 0 4px rgba(85, 136, 163, 0.1);
        outline: none;
    }
    .pm-textarea-modern {
        min-height: 100px;
        resize: vertical;
    }
    
    /* Table Styling */
    .glass-table-card {
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px -5px rgba(0, 51, 78, 0.05);
        border-radius: 1.5rem;
        overflow: hidden;
    }
    .pm-table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .pm-table-modern thead th {
        background: #00334E;
        color: white;
        text-align: left;
        padding: 1rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .pm-table-modern tbody td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    /* Badges & Buttons */
    .file-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #f1f5f9;
        color: #00334E;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    .file-badge:hover {
        background: #00334E;
        color: white;
        border-color: #00334E;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    .action-btn i { width: 14px; height: 14px; }
    .action-btn-edit { background: #5588A3/10; color: #5588A3; }
    .action-btn-edit:hover { background: #5588A3; color: white; }
    .action-btn-delete { background: #ef4444/10; color: #ef4444; }
    .action-btn-delete:hover { background: #ef4444; color: white; }
    
    .glow-orange:hover { box-shadow: 0 8px 20px -4px rgba(245, 158, 11, 0.4); }
    .glow-blue:hover { box-shadow: 0 8px 20px -4px rgba(85, 136, 163, 0.4); }
    
    .animate-in {
        animation: slideUp 0.5s ease-out forwards;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
function auditAnim() {
    return {
        init() {
            if (window.lucide) lucide.createIcons();
        }
    };
}
</script>
@endpush

