@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, {{ Auth::user()->full_name }}</p>
</div>

{{-- Stats Grid --}}
<div class="grid grid-4 gap-4 mb-6">
    <div class="stat-card animate-in">
        <div class="stat-icon primary"><i data-lucide="file-text" style="width:24px;height:24px;"></i></div>
        <div>
            <div class="stat-value">{{ $totalReports }}</div>
            <div class="stat-label">Total Laporan</div>
        </div>
    </div>
    <div class="stat-card animate-in" style="animation-delay:.05s">
        <div class="stat-icon warning"><i data-lucide="clock" style="width:24px;height:24px;"></i></div>
        <div>
            <div class="stat-value">{{ $pendingReports }}</div>
            <div class="stat-label">Menunggu Review</div>
        </div>
    </div>
    <div class="stat-card animate-in" style="animation-delay:.1s">
        <div class="stat-icon success"><i data-lucide="check-circle" style="width:24px;height:24px;"></i></div>
        <div>
            <div class="stat-value">{{ $resolvedReports }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    <div class="stat-card animate-in" style="animation-delay:.15s">
        @if(Auth::user()->canCreateReport())
        <div class="stat-icon primary"><i data-lucide="star" style="width:24px;height:24px;"></i></div>
        <div>
            <div class="stat-value">{{ $myPoints }}</div>
            <div class="stat-label">Poin Saya</div>
        </div>
        @else
        <div class="stat-icon primary"><i data-lucide="users" style="width:24px;height:24px;"></i></div>
        <div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total User</div>
        </div>
        @endif
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-2 gap-4 mb-6">
    {{-- Chart 1: Tren Laporan Bulanan --}}
    <div class="card animate-in" style="animation-delay:.2s">
        <div class="card-header">
            <h3>Tren Laporan Bulanan</h3>
        </div>
        <div class="card-body">
            <canvas id="chartMonthly" height="260"></canvas>
        </div>
    </div>

    {{-- Chart 2: Status Laporan --}}
    <div class="card animate-in" style="animation-delay:.25s">
        <div class="card-header">
            <h3>Status Laporan</h3>
        </div>
        <div class="card-body" style="display:flex;justify-content:center;">
            <div style="max-width:300px;width:100%;">
                <canvas id="chartStatus" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-2 gap-4 mb-6">
    {{-- Chart 3: Laporan per Kategori --}}
    <div class="card animate-in" style="animation-delay:.3s">
        <div class="card-header">
            <h3>Laporan per Kategori</h3>
        </div>
        <div class="card-body">
            <canvas id="chartCategory" height="260"></canvas>
        </div>
    </div>

    {{-- Chart 4: Poin Bulanan (reporter) / User Stats --}}
    <div class="card animate-in" style="animation-delay:.35s">
        <div class="card-header">
            @if(Auth::user()->canCreateReport())
            <h3>Poin Bulanan Saya</h3>
            @else
            <h3>Distribusi Laporan per Prioritas</h3>
            @endif
        </div>
        <div class="card-body">
            @if(Auth::user()->canCreateReport())
            <canvas id="chartPoints" height="260"></canvas>
            @else
            <canvas id="chartPriority" height="260"></canvas>
            @endif
        </div>
    </div>
</div>

{{-- Recent reports --}}
<div class="card animate-in" style="animation-delay:.4s">
    <div class="card-header">
        <h3>Laporan Terbaru</h3>
        @if(Auth::user()->canCreateReport())
        <a href="{{ route('reports.my') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        @endif
    </div>
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
                    @forelse($recentReports as $report)
                    <tr>
                        <td class="font-medium">{{ $report->code }}</td>
                        <td><span class="badge badge-info">{{ $report->kategori }}</span></td>
                        <td>{{ $report->lokasi }}</td>
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
                                @if(Auth::user()->canCreateReport())
                                <p>Belum ada laporan. <a href="{{ route('reports.create') }}">Buat laporan pertama</a></p>
                                @else
                                <p>Belum ada laporan masuk.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 13;
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.pointStyle = 'circle';
    Chart.defaults.plugins.legend.labels.padding = 16;

    // === Tren Bulanan (Area / Line) ===
    const monthlyCtx = document.getElementById('chartMonthly').getContext('2d');
    const gradient = monthlyCtx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(12, 107, 175, 0.15)');
    gradient.addColorStop(1, 'rgba(12, 107, 175, 0.01)');

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: @json($chartMonthly['labels']),
            datasets: [{
                label: 'Jumlah Laporan',
                data: @json($chartMonthly['data']),
                borderColor: '#0c6baf',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0c6baf',
                pointBorderWidth: 2.5,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#0c6baf',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    cornerRadius: 10,
                    padding: 12,
                    titleFont: { weight: '600' },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, padding: 8 },
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    border: { display: false },
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { padding: 8 },
                }
            }
        }
    });

    // === Status Laporan (Doughnut) ===
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: @json($chartStatus['labels']),
            datasets: [{
                data: @json($chartStatus['data']),
                backgroundColor: ['#f59e0b', '#3b82f6', '#06b6d4', '#10b981', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 8,
                borderRadius: 4,
                spacing: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12, weight: '500' }, padding: 12 }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    cornerRadius: 10,
                    padding: 12,
                    callbacks: {
                        label: function(ctx) {
                            let total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            let pct = total > 0 ? Math.round((ctx.parsed / total) * 100) : 0;
                            return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });

    // === Laporan per Kategori (Bar) ===
    new Chart(document.getElementById('chartCategory'), {
        type: 'bar',
        data: {
            labels: @json($chartCategory['labels']),
            datasets: [{
                label: 'Jumlah',
                data: @json($chartCategory['data']),
                backgroundColor: [
                    'rgba(12, 107, 175, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                ],
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.65,
                maxBarThickness: 48,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    cornerRadius: 10,
                    padding: 12,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, padding: 8 },
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    border: { display: false },
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { padding: 8 },
                }
            }
        }
    });

    // === Poin Bulanan (Reporter) ===
    @if(Auth::user()->canCreateReport())
    const pointsCtx = document.getElementById('chartPoints').getContext('2d');
    const pGradient = pointsCtx.createLinearGradient(0, 0, 0, 260);
    pGradient.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
    pGradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

    new Chart(pointsCtx, {
        type: 'line',
        data: {
            labels: @json($chartPoints['labels']),
            datasets: [{
                label: 'Poin',
                data: @json($chartPoints['data']),
                borderColor: '#10b981',
                backgroundColor: pGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2.5,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#0f172a', cornerRadius: 10, padding: 12 }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 5, padding: 8 }, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false }, ticks: { padding: 8 } }
            }
        }
    });
    @else
    // === Distribusi Prioritas (Horizontal Bar for Admin/PJ Area) ===
    const priorityData = @json($chartStatus);
    new Chart(document.getElementById('chartPriority'), {
        type: 'bar',
        data: {
            labels: priorityData.labels,
            datasets: [{
                data: priorityData.data,
                backgroundColor: [
                    'rgba(245, 158, 11, 0.15)',
                    'rgba(59, 130, 246, 0.15)',
                    'rgba(6, 182, 212, 0.15)',
                    'rgba(16, 185, 129, 0.15)',
                    'rgba(239, 68, 68, 0.15)',
                ],
                borderColor: ['#f59e0b', '#3b82f6', '#06b6d4', '#10b981', '#ef4444'],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.7,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', cornerRadius: 10, padding: 12 } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.04)' }, border: { display: false } },
                y: { grid: { display: false }, border: { display: false } }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection
