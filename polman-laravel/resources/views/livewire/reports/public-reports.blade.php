<div>
    <div class="page-header">
        <h1>Laporan Publik</h1>
        <p>Lihat laporan improvement yang telah disetujui dan sedang ditindaklanjuti</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-4 gap-4 mb-6">
        <div class="stat-card animate-in">
            <div class="stat-icon primary"><i data-lucide="file-text" style="width:24px;height:24px;"></i></div>
            <div>
                <div class="stat-value">{{ number_format($totalReports) }}</div>
                <div class="stat-label">Total Laporan</div>
            </div>
        </div>
        <div class="stat-card animate-in">
            <div class="stat-icon success"><i data-lucide="check-circle" style="width:24px;height:24px;"></i></div>
            <div>
                <div class="stat-value">{{ number_format($approvedReports) }}</div>
                <div class="stat-label">Disetujui</div>
            </div>
        </div>
        <div class="stat-card animate-in">
            <div class="stat-icon warning"><i data-lucide="clock" style="width:24px;height:24px;"></i></div>
            <div>
                <div class="stat-value">{{ number_format($pendingReports) }}</div>
                <div class="stat-label">Menunggu Review</div>
            </div>
        </div>
        <div class="stat-card animate-in">
            <div class="stat-icon danger"><i data-lucide="x-circle" style="width:24px;height:24px;"></i></div>
            <div>
                <div class="stat-value">{{ number_format($rejectedReports) }}</div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body flex gap-3 items-center" style="display:flex; gap:12px; align-items:center;">
            <select wire:model.live="filterStatus" class="form-select" style="max-width:160px;">
                <option value="approved">Disetujui</option>
                <option value="pending">Menunggu Review</option>
                <option value="rejected">Ditolak</option>
            </select>
            <select wire:model.live="filterKategori" class="form-select" style="max-width:160px;">
                <option value="">Semua Kategori</option>
                <option value="5R">5R</option>
                <option value="7S">7S</option>
                <option value="K3">K3</option>
            </select>
        </div>
    </div>

    {{-- Reports Table --}}
    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Prioritas</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td class="font-medium">{{ $report->code }}</td>
                            <td><span class="badge badge-info">{{ $report->kategori }}</span></td>
                            <td class="text-sm">{{ \Illuminate\Support\Str::limit($report->lokasi, 40) }}</td>
                            <td>
                                <span class="badge {{ $report->prioritas === 'tinggi' ? 'badge-danger' : ($report->prioritas === 'sedang' ? 'badge-warning' : 'badge-neutral') }}">
                                    {{ $report->prioritas_label }}
                                </span>
                            </td>
                            <td class="text-sm text-muted">{{ $report->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $report->status === 'approved' ? 'badge-success' : ($report->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i data-lucide="inbox" style="width:40px;height:40px;"></i>
                                    <p>Belum ada laporan dengan filter ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($reports->hasPages())
        <div class="card-footer">{{ $reports->links() }}</div>
        @endif
    </div>

    {{-- Info Box --}}
    <div class="card mt-6" style="background:rgba(12,107,175,0.05);border:1px solid rgba(12,107,175,0.2);">
        <div class="card-body flex gap-4" style="display:flex; gap:16px;">
            <i data-lucide="info" style="width:24px;height:24px;color:var(--primary);flex-shrink:0;"></i>
            <div>
                <h4 style="margin:0 0 8px 0;color:var(--text-dark);">Ingin Berkontribusi?</h4>
                <p style="margin:0;color:var(--text-muted);font-size:0.95rem;">
                    Laporan Anda akan membantu meningkatkan kualitas lingkungan kampus.
                    <a href="{{ route('register') }}" style="color:var(--primary);font-weight:500;text-decoration:none;">Daftar sekarang</a>
                    untuk mengirim laporan dan dapatkan poin!
                </p>
            </div>
        </div>
    </div>
</div>
