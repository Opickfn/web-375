@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- ─── Page Header ─── --}}
@push('styles')
<style>
    /* Stat Cards Styling */
    .pm-stat {
        border-left: 5px solid transparent;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    .pm-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    .stat-total { border-left-color: #00334E; }
    .stat-pending { border-left-color: #f59e0b; }
    .stat-resolved { border-left-color: #10b981; }
    .stat-points { border-left-color: #f59e0b; }
    .stat-users { border-left-color: #5588A3; }

    /* Table Design */
    .pm-table { border-collapse: collapse; width: 100%; }
    .pm-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .pm-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }
    .pm-table tbody tr:hover {
        background-color: #f1f5f9;
    }

    /* Badges High Contrast */
    .pm-badge { border-radius: 4px; padding: 0.25rem 0.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .pm-badge-danger { background-color: #ef4444 !important; color: white !important; border: none !important; font-weight: bold; }
    .pm-badge-warning { background-color: #f59e0b !important; color: white !important; border: none !important; font-weight: bold; }
    .pm-badge-info { background-color: #38bdf8 !important; color: white !important; border: none !important; font-weight: bold; }
    .pm-badge-success { background-color: #10b981 !important; color: white !important; border: none !important; font-weight: bold; }
    .pm-badge-accent { background-color: #00334E !important; color: white !important; border: none !important; font-weight: bold; }
    .pm-badge-neutral { background-color: #94a3b8 !important; color: white !important; border: none !important; font-weight: bold; }

    /* Animations */
    .pm-fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s forwards cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Child delay for staggered animation */
    .pm-grid-4 > div:nth-child(1) { animation-delay: 0.1s; }
    .pm-grid-4 > div:nth-child(2) { animation-delay: 0.2s; }
    .pm-grid-4 > div:nth-child(3) { animation-delay: 0.3s; }
    .pm-grid-4 > div:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush
<div class="pm-page-header pm-fade-in">
    <div>
        <h1>Selamat Datang, {{ explode(' ', Auth::user()->full_name)[0] }} 👋</h1>
        <p>Ikhtisar sistem improvement K3, 7S, dan 5R — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div style="display:flex;gap:0.6rem;margin-bottom:0.5rem;">
        @if(Auth::user()->canCreateReport())
        <a href="{{ route('reports.create') }}" class="pm-btn pm-btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;"></i> Submit Temuan
        </a>
        @endif
    </div>
</div>

{{-- ─── Stat Cards ─── --}}
<div class="pm-grid-4" style="margin-bottom:1.5rem;gap:1rem;">
    <div class="pm-stat pm-fade-in stat-total">
        <div class="pm-stat-icon"><i data-lucide="file-text" style="width:22px;height:22px;color:#5588A3;"></i></div>
        <div>
            <div class="pm-stat-value count-up" data-count="{{ $totalReports }}">0</div>
            <div class="pm-stat-label">Total Temuan</div>
        </div>
    </div>
    <div class="pm-stat pm-fade-in stat-pending">
        <div class="pm-stat-icon" style="background:rgba(245,158,11,0.12);border-color:rgba(245,158,11,0.25);">
            <i data-lucide="clock" style="width:22px;height:22px;color:#f59e0b;"></i>
        </div>
        <div>
            <div class="pm-stat-value count-up" data-count="{{ $pendingReports }}">0</div>
            <div class="pm-stat-label">Menunggu Review</div>
        </div>
    </div>
    <div class="pm-stat pm-fade-in stat-resolved">
        <div class="pm-stat-icon" style="background:rgba(16,185,129,0.12);border-color:rgba(16,185,129,0.25);">
            <i data-lucide="check-circle" style="width:22px;height:22px;color:#10b981;"></i>
        </div>
        <div>
            <div class="pm-stat-value count-up" data-count="{{ $resolvedReports }}">0</div>
            <div class="pm-stat-label">Selesai</div>
        </div>
    </div>
    @if(Auth::user()->canCreateReport())
    <div class="pm-stat pm-fade-in stat-points">
        <div class="pm-stat-icon" style="background:rgba(245,158,11,0.12);border-color:rgba(245,158,11,0.25);">
            <i data-lucide="star" style="width:22px;height:22px;color:#f59e0b;"></i>
        </div>
        <div>
            <div class="pm-stat-value count-up" data-count="{{ $myPoints }}">0</div>
            <div class="pm-stat-label">Poin Saya</div>
        </div>
    </div>
    @else
    <div class="pm-stat pm-fade-in stat-users">
        <div class="pm-stat-icon"><i data-lucide="users" style="width:22px;height:22px;color:#5588A3;"></i></div>
        <div>
            <div class="pm-stat-value count-up" data-count="{{ $totalUsers }}">0</div>
            <div class="pm-stat-label">Total User</div>
        </div>
    </div>
    @endif
</div>

{{-- ─── Charts Row 1 ─── --}}
<div class="pm-grid-2" style="margin-bottom:1.25rem;gap:1rem;">
    <div class="pm-card pm-fade-in">
        <div class="pm-card-header">
            <h3><i data-lucide="trending-up" style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:6px;color:#5588A3;"></i>Tren Temuan 6 Bulan</h3>
        </div>
        <div class="pm-card-body" style="padding-top:0.5rem;">
            <canvas id="chartMonthly" height="220"></canvas>
        </div>
    </div>
    <div class="pm-card pm-fade-in">
        <div class="pm-card-header">
            <h3><i data-lucide="pie-chart" style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:6px;color:#5588A3;"></i>Status Temuan</h3>
        </div>
        <div class="pm-card-body" style="display:flex;justify-content:center;padding-top:0.5rem;">
            <div style="max-width:280px;width:100%;"><canvas id="chartStatus" height="220"></canvas></div>
        </div>
    </div>
</div>

{{-- ─── Charts Row 2 ─── --}}
<div class="pm-grid-2" style="margin-bottom:1.25rem;gap:1rem;">
    <div class="pm-card pm-fade-in">
        <div class="pm-card-header">
            <h3><i data-lucide="bar-chart-2" style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:6px;color:#5588A3;"></i>Temuan per Kategori</h3>
        </div>
        <div class="pm-card-body" style="padding-top:0.5rem;">
            <canvas id="chartCategory" height="220"></canvas>
        </div>
    </div>
    <div class="pm-card pm-fade-in">
        <div class="pm-card-header">
            @if(Auth::user()->canCreateReport())
            <h3><i data-lucide="zap" style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:6px;color:#f59e0b;"></i>Poin Bulanan</h3>
            @else
            <h3><i data-lucide="layers" style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:6px;color:#5588A3;"></i>Distribusi Prioritas</h3>
            @endif
        </div>
        <div class="pm-card-body" style="padding-top:0.5rem;">
            @if(Auth::user()->canCreateReport())
            <canvas id="chartPoints" height="220"></canvas>
            @else
            <canvas id="chartPriority" height="220"></canvas>
            @endif
        </div>
    </div>
</div>

{{-- ─── Recent Reports Table ─── --}}
<div class="pm-card pm-fade-in">
    <div class="pm-card-header">
        <h3><i data-lucide="list" style="width:15px;height:15px;display:inline;vertical-align:middle;margin-right:6px;color:#5588A3;"></i>Temuan Terbaru</h3>
        @if(Auth::user()->canCreateReport())
        <a href="{{ route('reports.my') }}" class="pm-btn pm-btn-outline pm-btn-sm">Lihat Semua</a>
        @endif
    </div>
    <div class="pm-table-wrap">
        <table class="pm-table">
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
                    <td style="font-weight:700;font-family:monospace;color:#5588A3;">{{ $report->code }}</td>
                    <td><span class="pm-badge pm-badge-info">{{ $report->kategori }}</span></td>
                    <td style="color:rgba(8,8,6,0.95);max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $report->lokasi }}</td>
                    <td>
                        <span class="pm-badge {{ $report->prioritas==='tinggi' ? 'pm-badge-danger' : ($report->prioritas==='sedang' ? 'pm-badge-warning' : 'pm-badge-neutral') }}">
                            {{ ucfirst($report->prioritas) }}
                        </span>
                    </td>
                    <td>
                        <span class="pm-badge {{ match($report->status) {
                            'pending'     => 'pm-badge-warning',
                            'approved'    => 'pm-badge-accent',
                            'in_progress' => 'pm-badge-info',
                            'resolved'    => 'pm-badge-success',
                            'rejected'    => 'pm-badge-danger',
                            default       => 'pm-badge-neutral',
                        } }}">{{ $report->status_label }}</span>
                    </td>
                    <td style="color:rgba(232,232,232,0.5);font-size:0.8rem;">{{ $report->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6">
                    <div class="pm-empty">
                        <i data-lucide="inbox" style="width:36px;height:36px;"></i>
                        <p>Belum ada temuan.<br>
                            @if(Auth::user()->canCreateReport())
                            <a href="{{ route('reports.create') }}" style="color:#5588A3;">Submit temuan pertama</a>
                            @endif
                        </p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart defaults
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#475569'; // Slate-600 for better visibility
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 16;

    const accent = '#5588A3';
    const accentGlow = 'rgba(85,136,163,0.15)';

    // Monthly trend
    const mCtx = document.getElementById('chartMonthly').getContext('2d');
    const mGrad = mCtx.createLinearGradient(0, 0, 0, 220);
    mGrad.addColorStop(0, 'rgba(85,136,163,0.3)');
    mGrad.addColorStop(1, 'rgba(85,136,163,0.02)');
    new Chart(mCtx, {
        type: 'line',
        data: {
            labels: @json($chartMonthly['labels']),
            datasets: [{
                label: 'Temuan',
                data: @json($chartMonthly['data']),
                borderColor: accent,
                backgroundColor: mGrad,
                borderWidth: 2.5, fill: true, tension: 0.4,
                pointRadius: 4, pointBackgroundColor: '#00334E',
                pointBorderColor: accent, pointBorderWidth: 2,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(0,30,50,0.95)', cornerRadius: 10, padding: 12 } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { display: false }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });

    // Status doughnut
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: @json($chartStatus['labels']),
            datasets: [{
                data: @json($chartStatus['data']),
                backgroundColor: ['#f59e0b','#00334E','#38bdf8','#10b981','#ef4444'],
                borderWidth: 0, hoverOffset: 6, borderRadius: 4, spacing: 2,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } },
                tooltip: { backgroundColor: 'rgba(0,30,50,0.95)', cornerRadius: 10, padding: 12,
                    callbacks: { label: function(ctx) {
                        const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                        return ` ${ctx.label}: ${ctx.parsed} (${total > 0 ? Math.round(ctx.parsed/total*100) : 0}%)`;
                    }}
                }
            }
        }
    });

    // Category bar
    new Chart(document.getElementById('chartCategory'), {
        type: 'bar',
        data: {
            labels: @json($chartCategory['labels']),
            datasets: [{
                label: 'Jumlah',
                data: @json($chartCategory['data']),
                backgroundColor: ['#00334E','#10b981','#ef4444'],
                borderRadius: 8, borderSkipped: false, barPercentage: 0.65, maxBarThickness: 44,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(0,30,50,0.95)', cornerRadius: 10, padding: 12 } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { display: false }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });

    @if(Auth::user()->canCreateReport())
    const pCtx = document.getElementById('chartPoints').getContext('2d');
    const pGrad = pCtx.createLinearGradient(0, 0, 0, 220);
    pGrad.addColorStop(0, 'rgba(245,158,11,0.25)');
    pGrad.addColorStop(1, 'rgba(245,158,11,0.02)');
    new Chart(pCtx, {
        type: 'line',
        data: {
            labels: @json($chartPoints['labels']),
            datasets: [{
                label: 'Poin',
                data: @json($chartPoints['data']),
                borderColor: '#f59e0b', backgroundColor: pGrad,
                borderWidth: 2.5, fill: true, tension: 0.4,
                pointRadius: 4, pointBackgroundColor: '#00334E',
                pointBorderColor: '#f59e0b', pointBorderWidth: 2, pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(0,30,50,0.95)', cornerRadius: 10, padding: 12 } },
            scales: {
                y: { beginAtZero: true, grid: { display: false }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });
    @else
    new Chart(document.getElementById('chartPriority'), {
        type: 'bar',
        data: {
            labels: @json($chartStatus['labels']),
            datasets: [{ data: @json($chartStatus['data']),
                backgroundColor: ['rgba(245,158,11,0.6)','rgba(0,51,78,0.6)','rgba(56,189,248,0.6)','rgba(16,185,129,0.6)','rgba(239,68,68,0.6)'],
                borderColor: ['#f59e0b','#00334E','#38bdf8','#10b981','#ef4444'],
                borderWidth: 2, borderRadius: 8, borderSkipped: false, barPercentage: 0.7,
            }]
        },
        options: {
            indexAxis: 'y', responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(0,30,50,0.95)', cornerRadius: 10, padding: 12 } },
            scales: {
                x: { beginAtZero: true, grid: { display: false }, border: { display: false } },
                y: { grid: { display: false }, border: { display: false } }
            }
        }
    });
    @endif

    // Progressive Counting Animation
    if (typeof anime !== 'undefined') {
        anime({
            targets: '.count-up',
            innerHTML: function(el) {
                return [0, el.getAttribute('data-count')];
            },
            easing: 'easeOutExpo',
            round: 1,
            duration: 1500,
            delay: 300 // start after fade-in
        });
    }
});
</script>
@endpush
@endsection