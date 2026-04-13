<div>
    <div class="page-header">
        <h1>Review Laporan</h1>
        <p>Laporan yang menunggu persetujuan Anda</p>
    </div>

    <div class="card mb-4">
        <div class="card-body flex gap-3 items-center">
            <select wire:model.live="filterKategori" class="form-select" style="max-width:160px;">
                <option value="">Semua Kategori</option>
                <option value="5R">5R</option>
                <option value="7S">7S</option>
                <option value="K3">K3</option>
            </select>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Prioritas</th>
                            <th>Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td class="font-medium">{{ $report->code }}</td>
                            <td>
                                @if($report->reporter)
                                    {{ $report->reporter->full_name }}
                                @else
                                    <span class="badge badge-neutral">Publik</span>
                                @endif
                            </td>
                            <td><span class="badge badge-info">{{ $report->kategori }}</span></td>
                            <td>{{ \Illuminate\Support\Str::limit($report->lokasi, 25) }}</td>
                            <td>
                                <span class="badge {{ $report->prioritas === 'tinggi' ? 'badge-danger' : ($report->prioritas === 'sedang' ? 'badge-warning' : 'badge-neutral') }}">
                                    {{ $report->prioritas_label }}
                                </span>
                            </td>
                            <td class="text-sm text-muted">{{ $report->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="flex gap-2" style="justify-content:flex-end;">
                                    <button wire:click="approve({{ $report->id }})" class="btn btn-success btn-sm" onclick="return confirm('Setujui laporan {{ $report->code }}?')">
                                        <i data-lucide="check" style="width:14px;height:14px;"></i> Setujui
                                    </button>
                                    <button wire:click="reject({{ $report->id }})" class="btn btn-danger btn-sm" onclick="return confirm('Tolak laporan {{ $report->code }}?')">
                                        <i data-lucide="x" style="width:14px;height:14px;"></i> Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i data-lucide="check-circle" style="width:40px;height:40px;"></i>
                                    <p>Tidak ada laporan yang perlu direview.</p>
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
</div>
