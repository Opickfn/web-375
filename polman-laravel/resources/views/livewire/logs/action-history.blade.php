<div>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1>Log History</h1>
            <p>Lihat riwayat persetujuan, penolakan laporan, dan peringatan.</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="flex gap-2 mb-4">
                <button type="button" wire:click="$set('activeTab', 'reports')" class="btn {{ $activeTab === 'reports' ? 'btn-primary' : 'btn-outline' }}">Approval / Rejected</button>
                <button type="button" wire:click="$set('activeTab', 'warnings')" class="btn {{ $activeTab === 'warnings' ? 'btn-primary' : 'btn-outline' }}">Warnings</button>
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Cari</label>
                <input type="text" wire:model.debounce.300ms="search" class="form-input" placeholder="Cari nama laporan, reviewer, atau peringatan...">
            </div>

            @if($activeTab === 'reports')
                <div class="table-wrapper">
                    <table class="table">
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
                            <tr>
                                <td>{{ $report->code }}</td>
                                <td>{{ $report->title }}</td>
                                <td><span class="badge {{ $report->status === 'approved' ? 'badge-success' : 'badge-danger' }}">{{ $report->status_label }}</span></td>
                                <td>{{ $report->reviewer?->full_name ?? '-' }}</td>
                                <td>{{ $report->reviewed_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>{{ $report->review_notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i data-lucide="info" style="width:40px;height:40px;"></i>
                                        <p>Tidak ada riwayat approval atau rejection.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $reportLogs->links() }}</div>
            @else
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul Warning</th>
                                <th>Laporan Terkait</th>
                                <th>Penginput</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Dibuat Pada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warningLogs as $warning)
                            <tr>
                                <td>{{ $warning->id }}</td>
                                <td>{{ $warning->title }}</td>
                                <td>{{ $warning->report?->code ? $warning->report->code . ' - ' . $warning->report->title : '-' }}</td>
                                <td>{{ $warning->creator?->full_name ?? '-' }}</td>
                                <td>{{ $warning->severity_label }}</td>
                                <td>{{ $warning->status_label }}</td>
                                <td>{{ $warning->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i data-lucide="info" style="width:40px;height:40px;"></i>
                                        <p>Tidak ada riwayat warnings.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $warningLogs->links() }}</div>
            @endif
        </div>
    </div>
</div>
