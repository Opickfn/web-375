<div>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1>Laporan Saya</h1>
            <p>Daftar semua laporan yang Anda buat</p>
        </div>
        <a href="{{ route('reports.create') }}" class="btn btn-primary">
            <i data-lucide="plus" style="width:16px;height:16px;"></i>
            Buat Laporan
        </a>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body flex gap-3 flex-wrap items-center">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-input" placeholder="Cari lokasi atau deskripsi..." style="max-width:280px;">
            <select wire:model.live="filterKategori" class="form-select" style="max-width:160px;">
                <option value="">Semua Kategori</option>
                <option value="5R">5R</option>
                <option value="7S">7S</option>
                <option value="K3">K3</option>
            </select>
            <select wire:model.live="filterStatus" class="form-select" style="max-width:180px;">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Review</option>
                <option value="approved">Disetujui</option>
                <option value="in_progress">Dalam Proses</option>
                <option value="resolved">Selesai</option>
                <option value="rejected">Ditolak</option>
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
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td class="font-medium">{{ $report->code }}</td>
                            <td><span class="badge badge-info">{{ $report->kategori }}</span></td>
                            <td>{{ \Illuminate\Support\Str::limit($report->lokasi, 30) }}</td>
                            <td>
                                <span class="badge {{ $report->prioritas === 'tinggi' ? 'badge-danger' : ($report->prioritas === 'sedang' ? 'badge-warning' : 'badge-neutral') }}">
                                    {{ $report->prioritas_label }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ match($report->status) { 'pending' => 'badge-warning', 'approved' => 'badge-primary', 'in_progress' => 'badge-info', 'resolved' => 'badge-success', 'rejected' => 'badge-danger', default => 'badge-neutral' } }}">
                                    {{ $report->status_label }}
                                </span>
                            </td>
                            <td class="text-sm text-muted">{{ $report->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i data-lucide="inbox" style="width:40px;height:40px;"></i>
                                    <p>Belum ada laporan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($reports->hasPages())
        <div class="card-footer">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</div>
