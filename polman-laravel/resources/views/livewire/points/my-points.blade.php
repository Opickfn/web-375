<div>
    <div class="page-header">
        <h1>Poin Saya</h1>
        <p>Riwayat perolehan poin Anda</p>
    </div>

    <div class="stat-card mb-6 animate-in" style="max-width:300px;">
        <div class="stat-icon primary"><i data-lucide="star" style="width:24px;height:24px;"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalPoints) }}</div>
            <div class="stat-label">Total Poin</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Riwayat Poin</h3></div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Laporan</th>
                            <th>Deskripsi</th>
                            <th>Tipe</th>
                            <th class="text-right">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($points as $point)
                        <tr style="{{ $point->amount < 0 ? 'opacity:0.7;' : '' }}">
                            <td class="text-sm text-muted">{{ $point->created_at->format('d M Y H:i') }}</td>
                            <td class="font-medium">{{ $point->report?->code ?? '-' }}</td>
                            <td class="text-sm">{{ $point->description }}</td>
                            <td>
                                <span class="badge {{ match($point->type) { 'submit' => 'badge-primary', 'approved' => 'badge-success', 'bonus' => 'badge-warning', 'rejected' => 'badge-danger', default => 'badge-neutral' } }}">
                                    {{ ucfirst($point->type) }}
                                </span>
                            </td>
                            <td class="text-right font-semibold" style="color:{{ $point->amount >= 0 ? 'var(--success)' : 'var(--danger)' }};">{{ $point->amount >= 0 ? '+' : '' }}{{ $point->amount }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i data-lucide="star" style="width:40px;height:40px;"></i>
                                    <p>Belum ada poin. <a href="{{ route('reports.create') }}">Submit laporan untuk mendapatkan poin</a></p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($points->hasPages())
        <div class="card-footer">{{ $points->links() }}</div>
        @endif
    </div>
</div>
