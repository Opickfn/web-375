<div x-data="logAnim()" x-init="init()">
    <div class="pm-header" data-anim="slide-down">
        <div><h1 class="pm-h1">Riwayat Log</h1><p class="pm-sub">Catatan approval, penolakan laporan, dan peringatan aktif</p></div>
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
                <input type="text" wire:model.debounce.300ms="search" class="pm-search" placeholder="Cari laporan, reviewer...">
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
                        <th>Laporan</th>
                        <th>Status</th>
                        <th>Reviewer</th>
                        <th>Tanggal Review</th>
                        <th>Catatan</th>
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
                        <th>Laporan</th>
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
@endpush>
