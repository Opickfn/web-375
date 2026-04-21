<div x-data="reviewAnim()" x-init="init()">

    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Review Laporan</h1>
            <p class="pm-sub">Verifikasi laporan masuk yang menunggu persetujuan</p>
        </div>
        <button wire:click="export" class="pm-btn pm-btn-ghost">
            <i data-lucide="download" style="width:15px;height:15px;"></i> Export CSV
        </button>
    </div>

    @if(session('success'))
    <div class="pm-flash pm-flash-success" x-init="flashAnim($el)">
        <i data-lucide="check-circle" style="width:15px;height:15px;"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="pm-toolbar" data-anim="fade-up" data-delay="80">
        <div class="pm-search-wrap">
            <i data-lucide="search" class="pm-search-icon"></i>
            <input type="text" wire:model.live.debounce.300ms="search" class="pm-search" placeholder="Cari pelapor, lokasi…">
            @if($search)<button wire:click="$set('search','')" class="pm-search-clear"><i data-lucide="x" style="width:13px;height:13px;"></i></button>@endif
        </div>
        <div class="pm-filters">
            <select wire:model.live="filterKategori" class="pm-select">
                <option value="">Semua Kategori</option>
                <option value="5R">5R</option><option value="7S">7S</option><option value="K3">K3</option>
            </select>
            <select wire:model.live="filterPrioritas" class="pm-select">
                <option value="">Semua Prioritas</option>
                <option value="rendah">Rendah</option>
                <option value="sedang">Sedang</option>
                <option value="tinggi">Tinggi</option>
            </select>
            <select wire:model.live="perPage" class="pm-select" style="width:90px;">
                <option value="15">15</option><option value="25">25</option><option value="50">50</option>
            </select>
        </div>
    </div>

    @if($search || $filterKategori || $filterPrioritas)
    <div class="pm-chips" data-anim="fade-up" data-delay="100">
        @if($search)<span class="pm-chip"><i data-lucide="search" style="width:11px;height:11px;"></i> "{{ $search }}" <button wire:click="$set('search','')" class="pm-chip-x">×</button></span>@endif
        @if($filterKategori)<span class="pm-chip pm-chip-blue">{{ $filterKategori }} <button wire:click="$set('filterKategori','')" class="pm-chip-x">×</button></span>@endif
        @if($filterPrioritas)<span class="pm-chip pm-chip-orange">{{ ucfirst($filterPrioritas) }} <button wire:click="$set('filterPrioritas','')" class="pm-chip-x">×</button></span>@endif
        <button wire:click="$set('search','');$set('filterKategori','');$set('filterPrioritas','')" class="pm-chip-reset">Reset semua</button>
    </div>
    @endif

    <div class="pm-card" data-anim="fade-up" data-delay="120">
        <div wire:loading wire:target="search,filterKategori,filterPrioritas,sort,approve,reject" class="pm-loading-bar"></div>

        <div class="pm-table-wrap">
            <table class="pm-table" id="review-table">
                <thead>
                    <tr>
                        <th class="pm-th-sort" wire:click="sort('created_at')">Kode @include('components.sort-icon',['col'=>'created_at','sortBy'=>$sortBy,'sortDir'=>$sortDir])</th>
                        <th>Pelapor</th>
                        <th class="pm-th-sort" wire:click="sort('kategori')">Kategori @include('components.sort-icon',['col'=>'kategori','sortBy'=>$sortBy,'sortDir'=>$sortDir])</th>
                        <th>Lokasi</th>
                        <th class="pm-th-sort" wire:click="sort('prioritas')">Prioritas @include('components.sort-icon',['col'=>'prioritas','sortBy'=>$sortBy,'sortDir'=>$sortDir])</th>
                        <th>Tanggal</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr class="pm-row" wire:key="rv-{{ $report->id }}">
                        <td><span class="pm-code">{{ $report->code }}</span></td>
                        <td>
                            @if($report->reporter)
                            <div style="font-weight:600;font-size:0.85rem;">{{ $report->reporter->full_name }}</div>
                            <div style="font-size:0.73rem;color:var(--pm-text-d);">{{ ucfirst($report->reporter->user_type) }}</div>
                            @else
                            <span class="pm-badge pm-badge-neutral">Publik</span>
                            @endif
                        </td>
                        <td><span class="pm-badge pm-badge-{{ strtolower($report->kategori) }}">{{ $report->kategori }}</span></td>
                        <td class="pm-td-loc">{{ $report->location_breadcrumb }}</td>
                        <td>
                            <span class="pm-badge {{ $report->prioritas==='tinggi'?'pm-badge-danger':($report->prioritas==='sedang'?'pm-badge-warning':'pm-badge-neutral') }}">
                                {{ ucfirst($report->prioritas) }}
                            </span>
                        </td>
                        <td class="pm-td-date">{{ $report->created_at->format('d M Y') }}</td>
                        <td style="text-align:right;">
                            @if(Auth::user()->role !== 'pimpinan')
                            <div class="pm-action-group">
                                <button wire:click="approve({{ $report->id }})"
                                    onclick="return confirm('Setujui laporan {{ $report->code }}?')"
                                    class="pm-btn pm-btn-success pm-btn-sm pm-action-btn"
                                    title="Setujui">
                                    <i data-lucide="check" style="width:13px;height:13px;"></i>
                                    <span>Setujui</span>
                                </button>
                                <button wire:click="reject({{ $report->id }})"
                                    onclick="return confirm('Tolak laporan {{ $report->code }}?')"
                                    class="pm-btn pm-btn-danger pm-btn-sm pm-action-btn"
                                    title="Tolak">
                                    <i data-lucide="x" style="width:13px;height:13px;"></i>
                                    <span>Tolak</span>
                                </button>
                            </div>
                            @else
                            <span style="color:var(--pm-text-d);">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">
                        <div class="pm-empty">
                            <div class="pm-empty-icon"><i data-lucide="check-circle" style="width:32px;height:32px;"></i></div>
                            <p>Tidak ada laporan yang perlu direview.</p>
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pm-table-footer">
            <span class="pm-count">
                @if($reports->total() > 0)
                    {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari <strong>{{ $reports->total() }}</strong>
                @else Tidak ada hasil @endif
            </span>
            @if($reports->hasPages()){{ $reports->links() }}@endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function reviewAnim() {
    return {
        init() {
            this.$nextTick(() => {
                this.entrance();
                if (typeof Livewire !== 'undefined') {
                    Livewire.hook('morph.updated', () => {
                        document.querySelectorAll('#review-table tbody tr').forEach(r => delete r.dataset.rowed);
                        setTimeout(() => { this.animRows(); if(window.lucide) lucide.createIcons(); }, 40);
                    });
                }
            });
        },
        entrance() {
            const h = document.querySelector('[data-anim="slide-down"]');
            if (h && typeof anime !== 'undefined') {
                h.style.opacity='0'; h.style.transform='translateY(-20px)';
                anime({targets:h, opacity:[0,1], translateY:['-20px','0px'], duration:520, easing:'easeOutExpo'});
            }
            document.querySelectorAll('[data-anim="fade-up"]').forEach(el => {
                el.style.opacity='0'; el.style.transform='translateY(18px)';
                anime({targets:el, opacity:[0,1], translateY:['18px','0px'], duration:460, delay:parseInt(el.dataset.delay||0), easing:'easeOutCubic'});
            });
            this.animRows();
        },
        animRows() {
            const rows = document.querySelectorAll('#review-table tbody tr:not([data-rowed])');
            if (!rows.length || typeof anime === 'undefined') return;
            rows.forEach(r => r.dataset.rowed='1');
            anime({ targets: Array.from(rows), opacity:[0,1], translateX:['-14px','0px'], duration:380, delay:anime.stagger(40), easing:'easeOutCubic' });

            // Action buttons entrance
            const btns = document.querySelectorAll('.pm-action-btn:not([data-btnanim])');
            btns.forEach(b => b.dataset.btnanim='1');
            anime({ targets: Array.from(btns), scale:[0.7,1], opacity:[0,1], duration:300, delay:anime.stagger(20,{start:300}), easing:'easeOutBack' });
        },
        flashAnim(el) {
            if (!el || typeof anime==='undefined') return;
            el.style.opacity='0'; el.style.transform='translateY(-8px)';
            anime({targets:el, opacity:[0,1], translateY:['-8px','0px'], duration:400, easing:'easeOutBack'});
        }
    }
}
</script>
@endpush