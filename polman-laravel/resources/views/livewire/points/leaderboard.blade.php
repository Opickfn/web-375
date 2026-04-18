@auth
<div>
@else
@section('content')
<div style="max-width:900px;margin:100px auto 40px;padding:0 24px;">
@endauth

    <div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1>Leaderboard</h1>
            <p>Peringkat kontributor berdasarkan total poin</p>
        </div>
        @guest
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
            <i data-lucide="log-in" style="width:16px;height:16px;"></i> Login
        </a>
        @endguest
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:60px;">Rank</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Gedung / Jabatan</th>
                            <th>Laporan</th>
                            <th>Total Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaderboard as $idx => $user)
                        @php
                            $displayName = ($user->role === 'reporter' && !$user->show_name_on_landing)
                                ? 'Anonim'
                                : $user->full_name;
                            $rank = $leaderboard->firstItem() + $idx;
                        @endphp
                        <tr>
                            <td>
                                @if($rank <= 3)
                                    <span class="badge {{ $rank === 1 ? 'badge-warning' : ($rank === 2 ? 'badge-neutral' : 'badge-info') }}" style="font-size:.85rem;">
                                        #{{ $rank }}
                                    </span>
                                @else
                                    <span class="text-muted">{{ $rank }}</span>
                                @endif
                            </td>
                            <td class="font-medium">{{ $displayName }}</td>
                            <td>
                                <span class="badge {{ match($user->user_type) { 'mahasiswa' => 'badge-info', 'dosen' => 'badge-warning', default => 'badge-neutral' } }}">
                                    {{ ucfirst($user->user_type) }}
                                </span>
                            </td>
                            <td class="text-sm text-muted">{{ $user->gedung ?? $user->jabatan ?? '-' }}</td>
                            <td>{{ $user->total_reports }}</td>
                            <td><span class="font-semibold" style="color:var(--primary);">{{ number_format($user->total_points) }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i data-lucide="trophy" style="width:40px;height:40px;"></i>
                                    <p>Belum ada data poin.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($leaderboard->hasPages())
        <div class="card-footer flex flex-col md:flex-row items-center justify-between gap-3">
            <div class="text-sm text-slate-300">
                Menampilkan {{ $leaderboard->firstItem() }}-{{ $leaderboard->lastItem() }} dari total {{ $leaderboard->total() }} peringkat
            </div>
            <div>{{ $leaderboard->links() }}</div>
        </div>
        @endif
    </div>
</div>
