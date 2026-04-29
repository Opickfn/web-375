<div x-data="myReportsAnim()" x-init="init()">

    {{-- ── Page Header ── --}}
    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Temuan Saya</h1>
            <p class="pm-sub">Semua temuan yang Anda kirimkan · {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div style="display:flex;gap:0.6rem;align-items:center;">
            <button wire:click="export" class="pm-btn pm-btn-ghost" title="Export CSV">
                <i data-lucide="download" style="width:15px;height:15px;"></i>
                <span>Export CSV</span>
            </button>
            <a href="{{ route('reports.create') }}" class="pm-btn pm-btn-primary">
                <i data-lucide="plus" style="width:15px;height:15px;"></i>
                <span>Buat Temuan</span>
            </a>
        </div>
    </div>

    {{-- ── Toolbar: search + filter ── --}}
    <div class="pm-toolbar" data-anim="fade-up" data-delay="80">
        <div class="pm-search-wrap">
            <i data-lucide="search" class="pm-search-icon"></i>
            <input type="text" wire:model.live.debounce.300ms="search"
                class="pm-search" placeholder="Cari lokasi, deskripsi…" autocomplete="off">
            @if($search)
            <button wire:click="$set('search','')" class="pm-search-clear" title="Hapus pencarian">
                <i data-lucide="x" style="width:13px;height:13px;"></i>
            </button>
            @endif
        </div>
        <div class="pm-filters">
            <select wire:model.live="filterKategori" class="pm-select">
                <option value="">Semua Kategori</option>
                <option value="5R">5R</option>
                <option value="7S">7S</option>
                <option value="K3">K3</option>
            </select>
            <select wire:model.live="filterStatus" class="pm-select">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="approved">Disetujui</option>
                <option value="in_progress">Dalam Proses</option>
                <option value="resolved">Selesai</option>
                <option value="rejected">Ditolak</option>
            </select>
            <select wire:model.live="perPage" class="pm-select" style="width:90px;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    {{-- ── Active filters chips ── --}}
    @if($search || $filterKategori || $filterStatus)
    <div class="pm-chips" data-anim="fade-up" data-delay="100">
        @if($search)
        <span class="pm-chip">
            <i data-lucide="search" style="width:11px;height:11px;"></i> "{{ $search }}"
            <button wire:click="$set('search','')" class="pm-chip-x">×</button>
        </span>
        @endif
        @if($filterKategori)
        <span class="pm-chip pm-chip-blue">
            {{ $filterKategori }}
            <button wire:click="$set('filterKategori','')" class="pm-chip-x">×</button>
        </span>
        @endif
        @if($filterStatus)
        <span class="pm-chip pm-chip-teal">
            {{ $filterStatus }}
            <button wire:click="$set('filterStatus','')" class="pm-chip-x">×</button>
        </span>
        @endif
        <button wire:click="$set('search',''); $set('filterKategori',''); $set('filterStatus','')"
            class="pm-chip-reset">Reset semua</button>
    </div>
    @endif

    {{-- ── Table ── --}}
    <div class="pm-card" data-anim="fade-up" data-delay="120">

        {{-- Loading overlay --}}
        <div wire:loading wire:target="search,filterKategori,filterStatus,sort,perPage" class="pm-loading-bar"></div>

        <div class="pm-table-wrap">
            <table class="pm-table" id="my-reports-table">
                <thead>
                    <tr>
                        <th class="pm-th-sort" wire:click="sort('created_at')">
                            Kode
                            @include('components.sort-icon', ['col'=>'created_at','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th class="pm-th-sort" wire:click="sort('kategori')">
                            Kategori
                            @include('components.sort-icon', ['col'=>'kategori','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th>Lokasi</th>
                        <th class="pm-th-sort" wire:click="sort('prioritas')">
                            Prioritas
                            @include('components.sort-icon', ['col'=>'prioritas','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th class="pm-th-sort" wire:click="sort('status')">
                            Status
                            @include('components.sort-icon', ['col'=>'status','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr class="pm-row" wire:key="r-{{ $report->id }}">
                        <td>
                            <span class="pm-code">{{ $report->code }}</span>
                        </td>
                        <td>
                            <span class="pm-badge pm-badge-{{ strtolower($report->kategori) }}">{{ $report->kategori }}</span>
                        </td>
                        <td class="pm-td-loc">{{ $report->lokasi }}</td>
                        <td>
                            <span class="pm-badge {{ $report->prioritas==='tinggi'?'pm-badge-danger':($report->prioritas==='sedang'?'pm-badge-warning':'pm-badge-neutral') }}">
                                {{ ucfirst($report->prioritas) }}
                            </span>
                        </td>
                        <td>
                            <span class="pm-status-dot pm-status-{{ $report->status }}"></span>
                            <span class="pm-badge {{ match($report->status){'pending'=>'pm-badge-warning','approved'=>'pm-badge-accent','in_progress'=>'pm-badge-info','resolved'=>'pm-badge-success','rejected'=>'pm-badge-danger',default=>'pm-badge-neutral'} }}">
                                {{ $report->status_label }}
                            </span>
                        </td>
                        <td class="pm-td-date">{{ $report->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6">
                        <div class="pm-empty">
                            <div class="pm-empty-icon">
                                <i data-lucide="inbox" style="width:32px;height:32px;"></i>
                            </div>
                            <p>Belum ada temuan ditemukan.</p>
                            @if($search || $filterKategori || $filterStatus)
                            <button wire:click="$set('search','');$set('filterKategori','');$set('filterStatus','')" class="pm-btn pm-btn-ghost pm-btn-sm" style="margin-top:0.6rem;">
                                Hapus filter
                            </button>
                            @endif
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: info + pagination --}}
        <div class="pm-table-footer">
            <span class="pm-count">
                @if($reports->total() > 0)
                    Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari <strong>{{ $reports->total() }}</strong> temuan
                @else
                    Tidak ada hasil
                @endif
            </span>
            @if($reports->hasPages())
            <div>{{ $reports->links() }}</div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
function myReportsAnim() {
    return {
        init() {
            this.$nextTick(() => {
                this.runEntrance();
                this.watchLivewire();
            });
        },

        runEntrance() {
            const header = document.querySelector('[data-anim="slide-down"]');
            if (header && typeof anime !== 'undefined') {
                header.style.opacity = '0';
                header.style.transform = 'translateY(-20px)';
                anime({ targets: header, opacity:[0,1], translateY:['-20px','0px'], duration:500, easing:'easeOutExpo' });
            }

            const delayed = document.querySelectorAll('[data-anim="fade-up"]');
            delayed.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(18px)';
                const delay = parseInt(el.dataset.delay || 0);
                if (typeof anime !== 'undefined') {
                    anime({ targets: el, opacity:[0,1], translateY:['18px','0px'], duration:480, delay, easing:'easeOutCubic' });
                }
            });

            this.animateRows();
        },

        animateRows() {
            const rows = document.querySelectorAll('#my-reports-table tbody tr:not([data-rowed])');
            if (!rows.length || typeof anime === 'undefined') return;
            rows.forEach(r => r.dataset.rowed = '1');
            anime({
                targets: Array.from(rows),
                opacity: [0, 1],
                translateX: ['-14px', '0px'],
                duration: 380,
                delay: anime.stagger(40),
                easing: 'easeOutCubic'
            });

            // Badge pop
            const badges = document.querySelectorAll('#my-reports-table .pm-badge:not([data-popped])');
            badges.forEach(b => b.dataset.popped = '1');
            anime({
                targets: Array.from(badges),
                scale: [0.7, 1],
                opacity: [0, 1],
                duration: 320,
                delay: anime.stagger(25, {start: 200}),
                easing: 'easeOutBack'
            });
        },

        watchLivewire() {
            if (typeof Livewire === 'undefined') return;
            Livewire.hook('morph.updated', () => {
                // clear row markers so they reanimate
                document.querySelectorAll('#my-reports-table tbody tr').forEach(r => delete r.dataset.rowed);
                document.querySelectorAll('#my-reports-table .pm-badge').forEach(b => delete b.dataset.popped);
                setTimeout(() => this.animateRows(), 30);
                if (window.lucide) lucide.createIcons();
            });
        }
    }
}
</script>
@endpush