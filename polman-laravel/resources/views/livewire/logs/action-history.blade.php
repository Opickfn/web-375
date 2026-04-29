<div x-data="logAnim()" x-init="init()">
    <div class="pm-header" data-anim="slide-down">
        <div><h1 class="pm-h1">Riwayat Log</h1><p class="pm-sub">Catatan approval, penolakan temuan, dan peringatan aktif</p></div>
        <button wire:click="exportLog" class="pm-btn pm-btn-ghost">
            <i data-lucide="download" style="width:15px;height:15px;"></i> Export
        </button>
    </div>

    <div class="pm-card" style="margin-bottom:1.25rem;" data-anim="fade-up" data-delay="80">
        <div class="pm-card-body" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <div class="pm-tabs" style="display: flex; align-items: center; gap: 8px; margin: 0 !important;">
                <button type="button" wire:click="$set('activeTab','reports')" class="pm-tab {{ $activeTab === 'reports' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="clipboard-check" style="width:14px;height:14px;"></i> Approval / Rejected
                </button>
                <button type="button" wire:click="$set('activeTab','warnings')" class="pm-tab {{ $activeTab === 'warnings' ? 'active' : '' }}" style="display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="alert-triangle" style="width:14px;height:14px;"></i> Peringatan
                </button>
            </div>
            <div class="pm-search-wrap" style="flex:1;min-width:200px;">
                <i data-lucide="search" class="pm-search-icon"></i>
                <input type="text" wire:model.debounce.300ms="search" class="pm-search" placeholder="Cari temuan, reviewer...">
                @if($search)
                <button wire:click="$set('search','')" class="pm-search-clear">
                    <i data-lucide="x" style="width:13px;height:13px;"></i>
                </button>
                @endif
            </div>
        </div>
    </div>

    @if($activeTab === 'reports')
    <div class="pm-card" data-anim="fade-up" data-delay="100">
        <div wire:loading wire:target="search,activeTab" class="pm-loading-bar"></div>
        <div class="pm-table-wrap">
            <table class="pm-table" id="log-rpt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Temuan</th>
                        <th>Status</th>
                        <th>Reviewer</th>
                        <th>Tanggal Review</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportLogs as $report)
                    <tr class="pm-row" wire:key="lr-{{ $report->id }}">
                        <td><span class="pm-code">{{ $report->code }}</span></td>
                        <td style="max-width:200px;">{{ $report->deskripsi }}</td>
                        <td><span class="pm-badge {{ $report->status === 'approved' ? 'pm-badge-success' : 'pm-badge-danger' }}">{{ $report->status_label }}</span></td>
                        <td>{{ $report->reviewer->full_name ?? '—' }}</td>
                        <td>{{ $report->reviewed_at?->format('d M Y H:i') ?? '—' }}</td>
                        <td style="color:var(--pm-text-d);">{{ $report->review_notes ?? '—' }}</td>
                        <td>
                            <button wire:click="showDetail({{ $report->id }})" class="pm-btn pm-btn-ghost pm-btn-icon">
                                <i data-lucide="eye" style="width:14px;height:14px;"></i>
                            </button>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center p-8">Tidak ada riwayat approval/rejection</td></tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        <div class="pm-table-footer">
            <span class="pm-count">
                @if($reportLogs->total() > 0)
                    {{ $reportLogs->firstItem() }}–{{ $reportLogs->lastItem() }} dari <strong>{{ $reportLogs->total() }}</strong>
                @else
                    Tidak ada hasil
                @endif
            </span>
            @if($reportLogs->hasPages())
                {{ $reportLogs->links() }}
            @endif
        </div>
    </div>
    @else
    <div class="pm-card" data-anim="fade-up" data-delay="100">
        <div wire:loading wire:target="search,activeTab" class="pm-loading-bar"></div>
        <div class="pm-table-wrap">
            <table class="pm-table" id="log-warn-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul Warning</th>
                        <th>Temuan</th>
                        <th>Penginput</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warningLogs as $w)
                    <tr class="pm-row" wire:key="lw-{{ $w->id }}">
                        <td>{{ $w->id }}</td>
                        <td>{{ $w->title }}</td>
                        <td><span class="pm-code">{{ $w->report->code ?? '—' }}</span></td>
                        <td>{{ $w->creator->full_name ?? '—' }}</td>
                        <td><span class="pm-badge {{ $w->severity === 'high' ? 'pm-badge-danger' : ($w->severity === 'medium' ? 'pm-badge-warning' : 'pm-badge-info') }}">{{ $w->severity_label }}</span></td>
                        <td><span class="pm-badge {{ $w->status === 'active' ? 'pm-badge-success' : ($w->status === 'inactive' ? 'pm-badge-neutral' : 'pm-badge-danger') }}">{{ $w->status_label }}</span></td>
                        <td>{{ $w->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center p-8">Tidak ada riwayat warnings</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pm-table-footer">
            <span class="pm-count">
                @if($warningLogs->total() > 0)
                    {{ $warningLogs->firstItem() }}–{{ $warningLogs->lastItem() }} dari <strong>{{ $warningLogs->total() }}</strong>
                @else
                    Tidak ada hasil
                @endif
            </span>
            @if($warningLogs->hasPages())
                {{ $warningLogs->links() }}
            @endif
        </div>
    </div>
    @endif
    {{-- Modal Detail Laporan (Identik dengan Review) --}}                     
    @if($showDetailModal && $selectedReport)
    <div class="pm-modal-backdrop" style="display:flex; align-items:center; justify-content:center; z-index: 1000; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
        <div class="pm-modal-content" style="max-width: 900px; width: 95%; max-height: 95vh; overflow: hidden; border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);">
            
            {{-- Header: Simple & Elegant --}}
            <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: flex-start; background: #fff;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">{{ $selectedReport->code }}</h2>
                        <span class="pm-badge {{ $selectedReport->status === 'approved' ? 'pm-badge-success' : 'pm-badge-danger' }}" style="text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.025em;">
                            {{ $selectedReport->status }}
                        </span>
                    </div>
                    <p style="color: #64748b; font-size: 0.875rem;">Detail riwayat persetujuan temuan kampus</p>
                </div>
                <button wire:click="closeDetail" class="pm-btn-icon pm-btn-ghost" style="border-radius: 50%;">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>

            <div class="pm-modal-body" style="padding: 1.5rem; overflow-y: auto; max-height: calc(95vh - 140px); background: #f8fafc;">
                
                <div class="pm-grid-2" style="gap: 1.5rem; align-items: start;">
                    
                    {{-- KIRI: KARTU INFORMASI --}}
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        {{-- Card Pengusul --}}
                        <div style="background: #fff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <h4 style="font-size: 0.75rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 1rem; letter-spacing: 0.05em;">Informasi Pengusul & Lokasi</h4>
                            
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                                <div style="width: 40px; height: 40px; background: var(--pm-primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    {{ substr($selectedReport->reporter->full_name ?? 'Guest', 0, 1) }}
                                </div>
                                <div>
                                    <p style="font-weight: 600; color: #1e293b; margin: 0;">
                                        {{ $selectedReport->reporter?->full_name ?? 'Publik' }}
                                    </p>
                                    <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                                        {{ $selectedReport->reporter?->email ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <div style="padding-top: 1rem; border-top: 1px dashed #e2e8f0;">
                                <label style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">Lokasi Temuan</label>
                                <p style="font-weight: 500; color: #334155;"><i data-lucide="map-pin" style="width:14px; height:14px; display:inline; margin-right:4px;"></i> {{ $selectedReport->lokasi  ?? $selectedReport->location->name}}</p>
                            </div>
                        </div>

                        {{-- Card Bukti Foto --}}
                        @if($selectedReport->image_path)
                        <div style="background: #fff; padding: 0.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <img src="{{ Storage::url($selectedReport->image_path) }}" 
                                style="width: 100%; border-radius: 6px; display: block; cursor: zoom-in;">
                        </div>
                        @endif
                    </div>

                    {{-- KANAN: KARTU DESKRIPSI & HASIL REVIEW --}}
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        
                        {{-- Deskripsi Card --}}
                        <div style="background: #fff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <h4 style="font-size: 0.75rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 0.75rem;">Deskripsi Temuan</h4>
                            <p style="color: #334155; line-height: 1.6; font-size: 0.935rem; white-space: pre-line;">{{ $selectedReport->deskripsi }}</p>
                            
                            <div style="margin-top: 1rem; display: flex; gap: 1rem; font-size: 0.8rem;">
                                <span style="background: #f1f5f9; padding: 0.25rem 0.6rem; border-radius: 4px; color: #475569;">Kategori: <strong>{{ $selectedReport->kategori ?? '-' }}</strong></span>
                                <span style="background: #f1f5f9; padding: 0.25rem 0.6rem; border-radius: 4px; color: #475569;">Prioritas: <strong>{{ $selectedReport->prioritas ?? '-' }}</strong></span>
                            </div>
                        </div>

                        {{-- Reviewer Card (Highlight) --}}
                        <div style="background: #fff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0; border-left: 4px solid {{ $selectedReport->status === 'approved' ? '#10b981' : '#ef4444' }};">
                            <h4 style="font-size: 0.75rem; text-transform: uppercase; color: {{ $selectedReport->status === 'approved' ? '#059669' : '#dc2626' }}; font-weight: 700; margin-bottom: 0.75rem;">Hasil Review</h4>
                            
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; font-size: 0.75rem; color: #64748b;">Catatan Reviewer:</label>
                                <p style="color: #1e293b; font-weight: 500; margin-top: 0.25rem;">{{ $selectedReport->review_notes ?? 'Tidak ada catatan.' }}</p>
                            </div>

                            <div style="font-size: 0.75rem; color: #94a3b8; display: flex; flex-direction: column; gap: 0.25rem;">
                                <p>Reviewer: <strong>{{ $selectedReport->reviewer->full_name ?? '—' }}</strong></p>
                                <p>Waktu: {{ $selectedReport->reviewed_at?->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div style="padding: 1rem 1.5rem; background: #fff; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
                <button wire:click="closeDetail" class="pm-btn pm-btn-primary" style="padding: 0.5rem 2rem;">Tutup</button>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function logAnim() {
    return {
        init() {},
        ar() {}
    };
}
</script>
@endpush
