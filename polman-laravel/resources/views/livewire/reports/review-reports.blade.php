<div x-data="reviewAnim()" x-init="init()">

    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Review Temuan</h1>
            <p class="pm-sub">Verifikasi temuan masuk yang menunggu persetujuan</p>
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
            <input type="text" wire:model.live.debounce.300ms="search" class="pm-search" placeholder="Cari pengusul, lokasi…">
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
                        <th>Pengusul</th>
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
                            <div class="pm-action-group" style="justify-content: flex-end;">
                                {{-- Tombol Detail: Bisa diakses SEMUA ROLE termasuk Pimpinan --}}
                                <button wire:click="viewDetail({{ $report->id }})" 
                                    class="pm-btn pm-btn-ghost pm-btn-sm pm-action-btn" 
                                    title="Lihat Detail">
                                    <i data-lucide="eye" style="width:13px;height:13px;"></i>
                                    <span>Detail</span>
                                </button>

                                {{-- Tombol Aksi: Hanya untuk selain Pimpinan --}}
                                @if(Auth::user()->role !== 'pimpinan')
                                    <button wire:click="approve({{ $report->id }})"
                                        onclick="return confirm('Setujui temuan {{ $report->code }}?')"
                                        class="pm-btn pm-btn-success pm-btn-sm pm-action-btn"
                                        title="Setujui">
                                        <i data-lucide="check" style="width:13px;height:13px;"></i>
                                        <span>Setujui</span>
                                    </button>
                                    <button wire:click="reject({{ $report->id }})"
                                        onclick="return confirm('Tolak temuan {{ $report->code }}?')"
                                        class="pm-btn pm-btn-danger pm-btn-sm pm-action-btn"
                                        title="Tolak">
                                        <i data-lucide="x" style="width:13px;height:13px;"></i>
                                        <span>Tolak</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="p-8">
                                <i data-lucide="inbox" style="width:40px;height:40px;color:#cbd5e1;margin:0 auto 1rem;"></i>
                                <p class="text-slate-500">Tidak ada laporan di area tugas Anda.</p>
                                <p class="text-xs text-slate-400">Hubungi Admin jika area tugas belum di-set.</p>
                            </div>
                        </td>
                    </tr>
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
    {{-- Container Utama Alpine untuk menampung state Lightbox --}}
    <div x-data="{ lightboxOpen: false, lightboxImage: '' }">

        {{-- MODAL DETAIL --}}
        @if($showModal && $selectedReport)
        <div class="pm-modal-backdrop" 
            style="display: flex; align-items: flex-start; justify-content: center; background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(4px); z-index: 9999; padding-top: 80px; overflow-y: auto;"
            x-data 
            x-init="anime({targets:'.pm-modal-card', opacity:[0,1], translateY:[15,0], scale:[0.98,1], duration:350, easing:'easeOutQuad'})"
            @click.self="$wire.set('showModal', false)">
            
            <div class="pm-modal-card" style="max-width: 400px; width: 90%; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); margin-bottom: 40px; background: #fff;">
                
                {{-- Header --}}
                <div class="pm-modal-header" style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fff;">
                    <div>
                        <h2 class="pm-h2" style="font-size: 1rem; margin-bottom: 0;">Detail Laporan</h2>
                        <p style="font-size: 0.7rem; color: #94a3b8;">{{ $selectedReport->code }}</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="pm-btn-close" style="font-size: 1.2rem; color: #94a3b8; line-height: 1;">&times;</button>
                </div>
                
                <div class="pm-modal-body" style="padding: 1.25rem;">
                    
                    {{-- Info Row: Pengusul & Prioritas --}}
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.75rem; color: #64748b; padding-bottom: 0.75rem; border-bottom: 1px solid #f8fafc;">
                        <span>Oleh: <strong>{{ $selectedReport->user->full_name ?? 'Publik' }}</strong></span>
                        <span style="text-transform: uppercase; font-weight: 700; color: #3b82f6;">{{ $selectedReport->prioritas }}</span>
                    </div>

                    {{-- LOKASI (Tambahan Baru agar tidak terpotong) --}}
                    <div style="margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 4px;">
                            <i data-lucide="map-pin" style="width: 10px; height: 10px;"></i> Lokasi Temuan
                        </label>
                        <div style="padding: 0.75rem; background: #f1f5f9; border-radius: 8px; font-size: 0.8rem; color: #1e293b; line-height: 1.5;">
                            {{-- Panggil fungsi location_breadcrumb yang sama dengan di tabel --}}
                            <div style="font-weight: 600;">
                                {{ $selectedReport->location_breadcrumb ?? 'Lokasi tidak ditemukan' }}
                            </div>
                            
                            {{-- Jika ada detail tambahan seperti nomor meja atau pojok ruangan --}}
                            @if($selectedReport->detail_lokasi)
                                <div style="margin-top: 4px; padding-top: 4px; border-top: 1px solid #e2e8f0; font-style: italic; font-size: 0.75rem; color: #64748b;">
                                    Catatan: {{ $selectedReport->detail_lokasi }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Deskripsi --}}
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 4px;">Deskripsi</label>
                        <div style="padding: 0.75rem; background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 8px; font-size: 0.8rem; color: #334155; line-height: 1.4;">
                            {{ $selectedReport->deskripsi }}
                        </div>
                    </div>

                    {{-- Solusi --}}
                    @if($selectedReport->solusi)
                    <div style="margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; color: #10b981; text-transform: uppercase; margin-bottom: 4px;">
                            <i data-lucide="lightbulb" style="width: 10px; height: 10px;"></i> Solusi Disarankan
                        </label>
                        <div style="padding: 0.75rem; background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 8px; font-size: 0.8rem; font-style: italic; color: #166534;">
                            "{{ $selectedReport->solusi }}"
                        </div>
                    </div>
                    @endif

                    {{-- Bukti Foto (Klik untuk Zoom) --}}
                    @if($selectedReport->bukti)
                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Bukti Foto (Klik gambar)</label>
                        <div style="border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; cursor: pointer;" 
                            @click="lightboxImage = '{{ asset('storage/uploads/' . $selectedReport->bukti) }}'; lightboxOpen = true">
                            <img src="{{ asset('storage/uploads/' . $selectedReport->bukti) }}" 
                                style="width: 100%; max-height: 160px; object-fit: cover; display: block; transition: 0.3s;"
                                onmouseover="this.style.transform='scale(1.05)'" 
                                onmouseout="this.style.transform='scale(1)'">
                        </div>
                    </div>
                    @endif
                </div>

                <div class="pm-modal-footer" style="padding: 0.85rem 1.25rem; background: #f8fafc; border-top: 1px solid #f1f5f9;">
                    <button wire:click="$set('showModal', false)" 
                            class="pm-btn pm-btn-primary" 
                            style="width: 100%; padding: 0.5rem; border-radius: 8px; font-size: 0.85rem;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- MODAL LIGHTBOX (Zoom Foto) --}}
        <template x-if="lightboxOpen">
            <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 11000; display: flex; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(8px);"
                @click.self="lightboxOpen = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">
                
                <div style="position: relative; max-width: 95%; max-height: 90vh;">
                    <button @click="lightboxOpen = false" 
                            style="position: absolute; top: -45px; right: 0; color: white; font-size: 2.5rem; background: none; border: none; cursor: pointer;">
                        &times;
                    </button>
                    <img :src="lightboxImage" 
                        style="max-width: 100%; max-height: 85vh; border-radius: 4px; box-shadow: 0 0 30px rgba(0,0,0,0.5); border: 3px solid white;">
                </div>
            </div>
        </template>

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