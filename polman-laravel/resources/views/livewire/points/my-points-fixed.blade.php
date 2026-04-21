<div>

<div x-data="myPtsAnim()" x-init="init()">

    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Poin Saya</h1>
            <p class="pm-sub">Riwayat perolehan dan pengurangan poin Anda</p>
        </div>
        <button wire:click="export" class="pm-btn pm-btn-ghost">
            <i data-lucide="download" style="width:15px;height:15px;"></i> Export CSV
        </button>
    </div>

    <div class="pm-stat" style="max-width:340px;margin-bottom:1.5rem;" data-anim="fade-up" data-delay="80">
        <div class="pm-stat-icon" style="background:rgba(245,158,11,0.13);border-color:rgba(245,158,11,0.28);">
            <i data-lucide="star" style="width:24px;height:24px;color:#f59e0b;"></i>
        </div>
        <div>
            <div class="pm-stat-value" id="pts-counter">{{ number_format($totalPoints) }}</div>
            <div class="pm-stat-label">Total Poin Terkumpul</div>
        </div>
        <div style="margin-left:auto;">
            <div style="font-size:1.8rem;font-weight:800;color:rgba(245,158,11,0.25);">✦</div>
        </div>
    </div>

    <div class="pm-toolbar" data-anim="fade-up" data-delay="100">
        <div class="pm-search-wrap">
            <i data-lucide="search" class="pm-search-icon"></i>
            <input type="text" wire:model.live.debounce.300ms="search" class="pm-search" placeholder="Cari deskripsi…">
            @if($search)
            <button wire:click="$set('search','')" class="pm-search-clear"><i data-lucide="x" style="width:13px;height:13px;"></i></button>
            @endif
        </div>
        <div class="pm-filters">
            <select wire:model.live="filterType" class="pm-select">
                <option value="">Semua Tipe</option>
                <option value="submit">Submit</option>
                <option value="approved">Approved</option>
                <option value="bonus">Bonus</option>
                <option value="rejected">Rejected</option>
            </select>
            <select wire:model.live="perPage" class="pm-select" style="width:90px;">
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <div class="pm-card" data-anim="fade-up" data-delay="120">
        <div wire:loading wire:target="search,filterType,sort,perPage" class="pm-loading-bar"></div>
        <div class="pm-table-wrap">
            <table class="pm-table" id="pts-table">
                <thead>
                    <tr>
                        <th class="pm-th-sort" wire:click="sort('created_at')">Tanggal @include('components.sort-icon', ['col'=>'created_at', 'sortBy'=>$sortBy, 'sortDir'=>$sortDir])</th>
                        <th>Kode Laporan</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th style="text-align:right;" class="pm-th-sort" wire:click="sort('amount')">Poin @include('components.sort-icon', ['col'=>'amount', 'sortBy'=>$sortBy, 'sortDir'=>$sortDir])</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($points as $point)
                    <tr class="pm-row" wire:key="pt-{{ $point->id }}">
                        <td class="pm-td-date">{{ $point->created_at->format('d M Y H:i') }}</td>
                        <td><span class="pm-code">{{ $point->report?->code ?? '—' }}</span></td>
                        <td style="font-size:0.83rem;color:var(--pm-text-m);">{{ $point->description }}</td>
                        <td>
                            <span class="pm-badge {{ match($point->type) { 'submit' => 'pm-badge-accent', 'approved' => 'pm-badge-success', 'bonus' => 'pm-badge-gold', 'rejected' => 'pm-badge-danger', default => 'pm-badge-neutral' } }}">
                                {{ ucfirst($point->type) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <span style="font-weight:800;font-size:0.95rem;color:{{ $point->amount >= 0 ? '#4ade80' : '#f87171' }};">
                                {{ $point->amount >= 0 ? '+' : '' }}{{ $point->amount }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="pm-empty"><div class="pm-empty-icon"><i data-lucide="star" style="width:30px;height:30px;"></i></div><p>Belum ada riwayat poin.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pm-table-footer">
            <span class="pm-count">
                @if($points->total() > 0)
                    {{ $points->firstItem() }}–{{ $points->lastItem() }} dari <strong>{{ $points->total() }}</strong>
                @else
                    Tidak ada hasil
                @endif
            </span>
            @if($points->hasPages())
                {{ $points->links() }}
            @endif
        </div>
    </div>

    {{-- Inline script without @push --}}
    <script>
        function myPtsAnim() {
            return {
                init() {
                    this.$nextTick(() => {
                        const h = document.querySelector('[data-anim="slide-down"]');
                        if (h && typeof anime !== 'undefined') { 
                            h.style.opacity = '0'; 
                            h.style.transform = 'translateY(-18px)'; 
                            anime({
                                targets: h, 
                                opacity: [0,1], 
                                translateY: ['-18px','0px'], 
                                duration: 500, 
                                easing: 'easeOutExpo'
                            }); 
                        }
                        document.querySelectorAll('[data-anim="fade-up"]').forEach(el => {
                            el.style.opacity = '0'; 
                            el.style.transform = 'translateY(16px)';
                            anime({
                                targets: el, 
                                opacity: [0,1], 
                                translateY: ['16px','0px'], 
                                duration: 440, 
                                delay: parseInt(el.dataset.delay || 0), 
                                easing: 'easeOutCubic'
                            });
                        });

                        const counter = document.getElementById('pts-counter');
                        if (counter && typeof anime !== 'undefined') {
                            const target = parseInt(counter.textContent.replace(/[^\d]/g, '')) || 0;
                            if (target > 0) {
                                anime({ 
                                    targets: { v: 0 }, 
                                    v: [0, target], 
                                    round: 1, 
                                    duration: 1200, 
                                    delay: 400, 
                                    easing: 'easeOutExpo',
                                    update: a => { counter.textContent = Math.floor(a.animations[0].currentValue).toLocaleString('id'); }
                                });
                            }
                        }
                        this.animRows();
                    });
                },
                animRows() {
                    const rows = document.querySelectorAll('#pts-table tbody tr:not([data-rowed])');
                    if (!rows.length || typeof anime === 'undefined') return;
                    rows.forEach(r => r.dataset.rowed = '1');
                    anime({ 
                        targets: Array.from(rows), 
                        opacity: [0,1], 
                        translateX: ['-10px','0px'], 
                        duration: 350, 
                        delay: anime.stagger(35), 
                        easing: 'easeOutCubic' 
                    });
                }
            }
        }

        // Re-init on Livewire navigation
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                document.querySelectorAll('#pts-table tbody tr').forEach(r => delete r.dataset.rowed);
                setTimeout(() => { 
                    if (window.myPtsAnimInstance) window.myPtsAnimInstance.animRows(); 
                    if (window.lucide) lucide.createIcons(); 
                }, 40);
            });
        });
    </script>

</div>


