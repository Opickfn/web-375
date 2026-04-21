<div x-data="lbAnim()" x-init="init()">


    <div class="pm-header" data-anim="slide-down">
        <div>
            @if($limit ?? 0)
            <h1 class="pm-h1" style="font-size:1.4rem;">🏆 Top {{ $limit }} Leaderboard</h1>
            <p class="pm-sub">Kontributor terbaik periode ini</p>
            @else
            <h1 class="pm-h1">🏆 Leaderboard</h1>
            <p class="pm-sub">Peringkat kontributor berdasarkan total poin laporan disetujui</p>
            @endif
        </div>
        @if(!($limit ?? 0))
        <div style="display:flex;gap:0.6rem;align-items:center;">
            <button wire:click="export" class="pm-btn pm-btn-ghost">
                <i data-lucide="download" style="width:15px;height:15px;"></i> Export CSV
            </button>
            @guest
            <a href="{{ route('login') }}" class="pm-btn pm-btn-primary">
                <i data-lucide="log-in" style="width:15px;height:15px;"></i> Login
            </a>
            @endguest
        </div>
        @endif
    </div>

    {{-- Top 3 podium (hanya halaman pertama & tanpa filter) --}}
@if(!$search && !$filterType && !($limit ?? 0) && (($leaderboard instanceof \Illuminate\Pagination\LengthAwarePaginator && $leaderboard->currentPage() === 1 && $leaderboard->count() >= 3) || $leaderboard->count() >= 3))
    <div class="pm-podium" data-anim="fade-up" data-delay="80">
        @php $top3 = $leaderboard->take(3); @endphp
        @foreach($top3 as $idx => $u)
        @php
$dn = (($limit ?? 0) && $u->role==='reporter' && !$u->show_name_on_landing) ? 'Anonim' : $u->full_name;
            $medals = ['🥇','🥈','🥉'];
            $sizes  = ['1.2rem','1rem','1rem'];
            $heights= ['120px','100px','100px'];
            $order  = [2, 1, 3]; // center gold
        @endphp
        <div class="pm-podium-item" style="order:{{ $order[$idx] }};height:{{ $heights[$idx] }};" data-rank="{{ $idx+1 }}">
            <div class="pm-podium-medal">{{ $medals[$idx] }}</div>
            <div class="pm-podium-avatar" style="font-size:{{ $sizes[$idx] }};">{{ strtoupper(substr($dn,0,2)) }}</div>
            <div class="pm-podium-name">{{ Str::limit($dn,14) }}</div>
            <div class="pm-podium-pts">{{ number_format($u->total_points) }} pts</div>
        </div>
        @endforeach
    </div>
    @endif

@if(true)
    {{-- Toolbar --}}
    <div class="pm-toolbar" data-anim="fade-up" data-delay="100">
        <div class="pm-search-wrap">
            <i data-lucide="search" class="pm-search-icon"></i>
            <input type="text" wire:model.live.debounce.300ms="search" class="pm-search" placeholder="Cari nama…">
            @if($search)<button wire:click="$set('search','')" class="pm-search-clear"><i data-lucide="x" style="width:13px;height:13px;"></i></button>@endif
        </div>
        <div class="pm-filters">
            <select wire:model.live="filterType" class="pm-select">
                <option value="">Semua Tipe</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
                <option value="umum">Umum</option>
            </select>
            <select wire:model.live="selectedPeriod" class="pm-select">
                <option value="">Seluruh Periode</option>
                @foreach($periods as $period)
                <option value="{{ $period->id }}" {{ $selectedPeriod == $period->id ? 'selected' : '' }}>
                    {{ $period->name }} ({{ $period->start_date->format('d M Y') }} — {{ $period->end_date->format('d M Y') }})
                </option>
                @endforeach
            </select>
            @if(!($limit ?? 0))
            <select wire:model.live="perPage" class="pm-select" style="width:90px;">
                <option value="15">15</option><option value="25">25</option><option value="50">50</option>
            </select>
            @endif
        </div>
    </div>
    @endif

    <div class="pm-card" data-anim="fade-up" data-delay="140">
        <div wire:loading wire:target="search,filterType,sort,perPage" class="pm-loading-bar"></div>

        <div class="pm-table-wrap">
            <table class="pm-table" id="lb-table">
                <thead>
                    <tr>
                        <th style="width:60px;">Rank</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Gedung / Jabatan</th>
                        <th class="pm-th-sort" wire:click="sort('total_reports')">
                            Laporan @include('components.sort-icon',['col'=>'total_reports','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th class="pm-th-sort" style="text-align:right;" wire:click="sort('total_points')">
                            Poin @include('components.sort-icon',['col'=>'total_points','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                    </tr>
                </thead>
                <tbody>
@forelse($leaderboard as $idx => $user)
    @php
$dn   = (($limit ?? 0) && $user->role==='reporter' && !$user->show_name_on_landing) ? 'Anonim' : $user->full_name;
        $rank = ($leaderboard instanceof \Illuminate\Pagination\LengthAwarePaginator) ? $leaderboard->firstItem() + $idx : ($idx + 1);
    @endphp
                    <tr class="pm-row {{ $rank<=3?'pm-row-top':'' }}" wire:key="lb-{{ $user->id }}">
                        <td>
                            @if($rank===1)<span class="pm-rank-medal pm-gold">🥇</span>
                            @elseif($rank===2)<span class="pm-rank-medal pm-silver">🥈</span>
                            @elseif($rank===3)<span class="pm-rank-medal pm-bronze">🥉</span>
                            @else<span class="pm-rank-num">#{{ $rank }}</span>@endif
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.6rem;">
                                <div class="pm-mini-avatar">{{ strtoupper(substr($dn,0,2)) }}</div>
                                <span style="font-weight:600;font-size:0.88rem;">{{ $dn }}</span>
                            </div>
                        </td>
                        <td><span class="pm-badge {{ $user->user_type==='mahasiswa'?'pm-badge-info':($user->user_type==='dosen'?'pm-badge-warning':'pm-badge-neutral') }}">{{ ucfirst($user->user_type) }}</span></td>
                        <td class="pm-td-loc">{{ $user->gedung ?? $user->jabatan ?? '—' }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $user->total_reports }}</td>
                        <td style="text-align:right;">
                            <span class="pm-pts {{ $rank===1?'pm-pts-gold':($rank===2?'pm-pts-silver':($rank===3?'pm-pts-bronze':'')) }}">
                                {{ number_format($user->total_points) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="pm-empty"><div class="pm-empty-icon"><i data-lucide="trophy" style="width:32px;height:32px;"></i></div><p>Belum ada data poin.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(!($limit ?? 0))
        <div class="pm-table-footer">
            <span class="pm-count">
                @if($leaderboard->total() > 0)
                    {{ $leaderboard->firstItem() }}–{{ $leaderboard->lastItem() }} dari <strong>{{ $leaderboard->total() }}</strong> peringkat
                @else Tidak ada hasil @endif
            </span>
            @if($leaderboard->hasPages()){{ $leaderboard->links() }}@endif
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
function lbAnim() {
    return {
        init() {
            this.$nextTick(() => { this.entrance(); this.watchLivewire(); });
        },
        entrance() {
            const h = document.querySelector('[data-anim="slide-down"]');
            if (h && typeof anime!=='undefined') {
                h.style.opacity='0'; h.style.transform='translateY(-20px)';
                anime({targets:h, opacity:[0,1], translateY:['-20px','0px'], duration:520, easing:'easeOutExpo'});
            }
            document.querySelectorAll('[data-anim="fade-up"]').forEach(el => {
                el.style.opacity='0'; el.style.transform='translateY(18px)';
                anime({targets:el, opacity:[0,1], translateY:['18px','0px'], duration:460, delay:parseInt(el.dataset.delay||0), easing:'easeOutCubic'});
            });

            // Podium items special entrance
            const podiumItems = document.querySelectorAll('.pm-podium-item');
            podiumItems.forEach((item, i) => {
                item.style.opacity='0';
                item.style.transform='translateY(40px) scale(0.8)';
            });
            if (podiumItems.length && typeof anime!=='undefined') {
                anime({
                    targets: Array.from(podiumItems).sort((a,b)=>(parseInt(a.dataset.rank)-parseInt(b.dataset.rank))),
                    opacity:[0,1], translateY:['40px','0px'], scale:[0.8,1],
                    duration:600, delay:anime.stagger(120,{start:200}), easing:'easeOutElastic(1,0.6)'
                });
            }

            this.animRows();
        },
        animRows() {
            const rows = document.querySelectorAll('#lb-table tbody tr:not([data-rowed])');
            if (!rows.length || typeof anime==='undefined') return;
            rows.forEach(r => r.dataset.rowed='1');
            anime({ targets:Array.from(rows), opacity:[0,1], translateX:['-12px','0px'], duration:380, delay:anime.stagger(35), easing:'easeOutCubic' });

            // Points counter animation
            document.querySelectorAll('.pm-pts:not([data-counted])').forEach(el => {
                el.dataset.counted = '1';
                const target = parseInt(el.textContent.replace(/[^\d]/g,'')) || 0;
                if (target > 0) {
                    let start = 0;
                    anime({
                        targets: {val: 0},
                        val: [0, target],
                        round: 1,
                        duration: 900,
                        delay: 400,
                        easing: 'easeOutExpo',
                        update: function(anim) {
                            el.textContent = Math.floor(anim.animations[0].currentValue).toLocaleString('id');
                        }
                    });
                }
            });
        },
        watchLivewire() {
            if (typeof Livewire==='undefined') return;
            Livewire.hook('morph.updated', () => {
                document.querySelectorAll('#lb-table tbody tr').forEach(r => delete r.dataset.rowed);
                document.querySelectorAll('.pm-pts').forEach(p => delete p.dataset.counted);
                setTimeout(() => { this.animRows(); if(window.lucide) lucide.createIcons(); }, 40);
            });
        }
    }
}
</script>
@endpush