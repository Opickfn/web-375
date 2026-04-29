<div x-data="warnAnim()" x-init="init()">

    {{-- ── Header ── --}}
    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">
                <span style="display:inline-flex;align-items:center;gap:0.5rem;">
                    <i data-lucide="alert-triangle" style="width:22px;height:22px;color:#f59e0b;"></i>
                    Kelola Peringatan
                </span>
            </h1>
            <p class="pm-sub">Buat dan pantau peringatan bahaya aktif di lingkungan kampus</p>
        </div>
        <div style="display:flex;gap:0.6rem;">
            <button wire:click="exportWarnings" class="pm-btn pm-btn-ghost">
                <i data-lucide="download" style="width:15px;height:15px;"></i> Export CSV
            </button>
            <button wire:click="openForm" class="pm-btn pm-btn-primary" x-ref="addBtn"
                @mouseenter="hoverBtn($el)" @mouseleave="unhoverBtn($el)">
                <i data-lucide="plus" style="width:15px;height:15px;"></i> Tambah Peringatan
            </button>
        </div>
    </div>

    {{-- ── Flash ── --}}
    @if($message)
    <div class="pm-flash pm-flash-{{ $messageType==='success'?'success':'danger' }}" data-anim="flash"
        x-init="if(typeof anime!=='undefined'){$el.style.opacity=0;$el.style.transform='translateY(-10px)';anime({targets:$el,opacity:[0,1],translateY:['-10px','0px'],duration:400,easing:'easeOutBack'})}">
        <i data-lucide="{{ $messageType==='success'?'check-circle':'alert-circle' }}" style="width:15px;height:15px;flex-shrink:0;"></i>
        <span>{{ $message }}</span>
        <button wire:click="$set('message',null)" style="margin-left:auto;background:none;border:none;color:inherit;cursor:pointer;font-size:1.2rem;line-height:1;">×</button>
    </div>
    @endif

    {{-- ── Severity stat pills ── --}}
    <div class="pm-grid-3" style="gap:0.75rem;margin-bottom:1.25rem;" id="warn-stats">
        <div class="pm-stat" style="padding:1rem 1.2rem;">
            <div class="pm-stat-icon" style="background:rgba(248,113,113,0.12);border-color:rgba(248,113,113,0.28);width:42px;height:42px;">
                <i data-lucide="alert-octagon" style="width:18px;height:18px;color:#f87171;"></i>
            </div>
            <div>
                <div class="pm-stat-value" style="font-size:1.5rem;" id="ws-high">{{ $warnings->where('severity','high')->count() }}</div>
                <div class="pm-stat-label">Tinggi</div>
            </div>
        </div>
        <div class="pm-stat" style="padding:1rem 1.2rem;">
            <div class="pm-stat-icon" style="background:rgba(245,158,11,0.12);border-color:rgba(245,158,11,0.28);width:42px;height:42px;">
                <i data-lucide="alert-triangle" style="width:18px;height:18px;color:#f59e0b;"></i>
            </div>
            <div>
                <div class="pm-stat-value" style="font-size:1.5rem;" id="ws-med">{{ $warnings->where('severity','medium')->count() }}</div>
                <div class="pm-stat-label">Sedang</div>
            </div>
        </div>
        <div class="pm-stat" style="padding:1rem 1.2rem;">
            <div class="pm-stat-icon" style="background:rgba(56,189,248,0.12);border-color:rgba(56,189,248,0.28);width:42px;height:42px;">
                <i data-lucide="info" style="width:18px;height:18px;color:#38bdf8;"></i>
            </div>
            <div>
                <div class="pm-stat-value" style="font-size:1.5rem;" id="ws-low">{{ $warnings->where('severity','low')->count() }}</div>
                <div class="pm-stat-label">Rendah</div>
            </div>
        </div>
    </div>

    {{-- ── Toolbar ── --}}
    <div class="pm-toolbar" data-anim="fade-up" data-delay="80">
        <div class="pm-search-wrap">
            <i data-lucide="search" class="pm-search-icon"></i>
            <input type="text" wire:model.live="search" class="pm-search" placeholder="Cari judul atau deskripsi…">
            @if($search)
            <button wire:click="$set('search','')" class="pm-search-clear">
                <i data-lucide="x" style="width:13px;height:13px;"></i>
            </button>
            @endif
        </div>
        <div class="pm-filters">
            <select wire:model.live="filterSeverity" class="pm-select">
                <option value="all">Semua Level</option>
                <option value="high">Tinggi</option>
                <option value="medium">Sedang</option>
                <option value="low">Rendah</option>
            </select>
            <select wire:model.live="filterStatus" class="pm-select">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
                <option value="expired">Expired</option>
            </select>
            <select wire:model.live="perPage" class="pm-select" style="width:90px;">
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    @if($search || $filterSeverity !== 'all' || $filterStatus !== 'all')
    <div class="pm-chips" data-anim="fade-up" data-delay="90">
        @if($search)
        <span class="pm-chip"><i data-lucide="search" style="width:11px;height:11px;"></i> "{{ $search }}"
            <button wire:click="$set('search','')" class="pm-chip-x">×</button></span>
        @endif
        @if($filterSeverity !== 'all')
        <span class="pm-chip pm-chip-orange">Level: {{ ucfirst($filterSeverity) }}
            <button wire:click="$set('filterSeverity','all')" class="pm-chip-x">×</button></span>
        @endif
        @if($filterStatus !== 'all')
        <span class="pm-chip pm-chip-teal">{{ ucfirst($filterStatus) }}
            <button wire:click="$set('filterStatus','all')" class="pm-chip-x">×</button></span>
        @endif
        <button wire:click="$set('search','');$set('filterSeverity','all');$set('filterStatus','all')" class="pm-chip-reset">Reset semua</button>
    </div>
    @endif

    {{-- ── Form ── --}}
    @if($showForm)
    <div class="pm-card" style="margin-bottom:1.25rem;" data-anim="form-in" id="warn-form">
        <div class="pm-card-header">
            <h3>{{ $editingId ? '✏️ Edit Peringatan' : '➕ Tambah Peringatan' }}</h3>
            <button wire:click="closeForm" class="pm-btn pm-btn-ghost pm-btn-icon">
                <i data-lucide="x" style="width:14px;height:14px;"></i>
            </button>
        </div>
        <div class="pm-card-body">
            <form wire:submit="save">
                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Judul Peringatan</label>
                        <input type="text" wire:model="formTitle" class="pm-input" placeholder="Contoh: Peralatan rusak di bengkel CNC">
                        @error('formTitle') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Level Keparahan</label>
                        <select wire:model="formSeverity" class="pm-select-full">
                            <option value="low">🟢 Rendah</option>
                            <option value="medium" selected>🟡 Sedang</option>
                            <option value="high">🔴 Tinggi</option>
                        </select>
                        @error('formSeverity') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pm-form-group">
                    <label class="pm-label">Deskripsi Detail</label>
                    <textarea wire:model="formDescription" class="pm-textarea" rows="3"
                        placeholder="Jelaskan situasi, lokasi, dan dampak potensial secara detail…"></textarea>
                    @error('formDescription') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>

                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Status</label>
                        <select wire:model="formStatus" class="pm-select-full">
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                            <option value="expired">Expired</option>
                        </select>
                        @error('formStatus') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Berlaku Sampai <span style="color:var(--pm-text-d);font-weight:400;">(opsional)</span></label>
                        <input type="date" wire:model="formExpiresAt" class="pm-input">
                        @error('formExpiresAt') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Sumber Gambar</label>
                        <select wire:model.live="image_source" class="pm-select-full">
                            <option value="manual">📁 Upload Manual</option>
                            <option value="report">📄 Dari Temuan</option>
                        </select>
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Temuan Terkait <span x-show="$wire.image_source === 'report'" style="color:var(--pm-text-d);font-weight:400;">(wajib jika dari temuan)</span></label>
                        <select wire:model="formReportId" class="pm-select-full">
                            <option value="">— Tidak ada —</option>
                            @foreach($approvedReports as $r)
                            <option value="{{ $r->id }}">{{ $r->code }} — {{ Str::limit($r->deskripsi, 45) }}</option>
                            @endforeach
                        </select>
                        @error('formReportId') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div x-show="$wire.image_source === 'manual'" class="pm-form-group">
                    <label class="pm-label">Gambar Manual <span style="color:var(--pm-text-d);font-weight:400;">(JPG/PNG, max 2MB)</span></label>
                    <input type="file" wire:model="formImage" accept="image/*" class="pm-input">
                    @error('formImage') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>

                @if($editingId && $image_source === 'report' && $formReportId)
                <div class="pm-alert pm-alert-info">
                    <i data-lucide="image" style="width:16px;height:16px;color:#38bdf8;"></i>
                    <span>Gambar akan diambil dari temuan yang dipilih. Preview: <strong>{{ Report::find($formReportId)?->bukti ? 'Tersedia' : 'Tidak ada gambar' }}</strong></span>
                </div>
                @endif

                <div class="pm-form-group" style="display:flex;align-items:center;padding-top:1.6rem;">
                    <label style="display:flex;align-items:center;gap:0.65rem;cursor:pointer;font-size:0.85rem;color:var(--pm-text-m);">
                        <input type="checkbox" wire:model="formIsPublic"
                            style="accent-color:#5588A3;width:16px;height:16px;">
                        Tampilkan di halaman publik (landing page)
                    </label>
                </div>

                <!-- The "default hero image" option is only relevant when creating a new warning or if the existing warning is already set as default. -->
                <div class="pm-form-group" style="margin-top: 1rem; padding: 1rem; background: rgba(0,51,78,0.05); border-radius: 8px; border: 1px dashed rgba(0,51,78,0.2);">
                    <label class="pm-label" style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; margin-bottom: 0;">
                        <input type="checkbox" wire:model="formIsDefault" class="pm-checkbox" style="width: 18px; height: 18px;">
                        <div>
                            <span style="font-weight: bold; color: #00334E;">Jadikan Gambar Default Hero</span>
                            <p style="font-size: 0.75rem; color: #64748b; margin: 0; line-height: 1.2;">
                                Jika dicentang, gambar ini akan tampil sebagai background utama di halaman depan saat tidak ada peringatan (warnings) aktif.
                            </p>
                        </div>
                    </label>
                </div>

                <div style="display:flex;gap:0.65rem;margin-top:0.5rem;">
                    <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled">
                        <i data-lucide="save" style="width:14px;height:14px;"></i>
                        <span wire:loading.remove>{{ $editingId ? 'Perbarui' : 'Simpan' }}</span>
                        <span wire:loading>Menyimpan…</span>
                    </button>
                    <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ── Table ── --}}
    <div class="pm-card" data-anim="fade-up" data-delay="120">
        <div wire:loading wire:target="search,filterSeverity,filterStatus,sort,perPage,deleteWarning,toggleStatus" class="pm-loading-bar"></div>

        <div class="pm-table-wrap">
            <table class="pm-table" id="warn-table">
                <thead>
                    <tr>
                        <th class="pm-th-sort" wire:click="sort('title')">
                            Judul @include('components.sort-icon',['col'=>'title','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th class="pm-th-sort" wire:click="sort('severity')">
                            Level @include('components.sort-icon',['col'=>'severity','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th class="pm-th-sort" wire:click="sort('status')">
                            Status @include('components.sort-icon',['col'=>'status','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th class="pm-th-sort" wire:click="sort('expires_at')">
                            Berlaku S/d @include('components.sort-icon',['col'=>'expires_at','sortBy'=>$sortBy,'sortDir'=>$sortDir])
                        </th>
                        <th>Publik</th>
                        <th>Temuan</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warnings as $warning)
                    <tr class="pm-row {{ $warning->severity==='high'?'pm-row-high':'' }}" wire:key="w-{{ $warning->id }}">
                        <td>
                            <div style="font-weight:600;font-size:0.875rem;color:var(--pm-text);">{{ $warning->title }}</div>
                            
                            <!-- If this warning is marked as the default hero, show a badge next to the title -->
                            @if($warning->is_default)
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 10px; background: #00334E; color: white; padding: 1px 8px; border-radius: 4px; margin-top: 4px; font-weight: bold;">
                                    <i data-lucide="image" style="width: 10px; height: 10px;"></i> DEFAULT HERO
                                </span>
                            @endif

                            <div style="font-size:0.73rem;color:var(--pm-text-d);margin-top:2px;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $warning->description }}
                            </div>
                        </td>
                        <td>
                            <span class="pm-badge pm-sev-{{ $warning->severity }}">
                                {{ $warning->severity==='high'?'🔴':($warning->severity==='medium'?'🟡':'🟢') }}
                                {{ $warning->severity_label }}
                            </span>
                        </td>
                        <td>
                            @if($warning->status === 'active')
                                <span class="pm-status-dot pm-status-in_progress"></span>
                            @endif
                            <span class="pm-badge {{ $warning->status==='active'?'pm-badge-success':($warning->status==='inactive'?'pm-badge-neutral':'pm-badge-danger') }}">
                                {{ $warning->status_label }}
                            </span>
                        </td>
                        <td class="pm-td-date">
                            @if($warning->expires_at)
                                {{ $warning->expires_at->format('d M Y') }}
                                @if($warning->expires_at->isPast())
                                <span class="pm-badge pm-badge-danger" style="font-size:0.62rem;padding:1px 5px;margin-left:3px;">Lewat</span>
                                @endif
                            @else
                                <span style="color:var(--pm-text-d);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($warning->is_public)
                            <span class="pm-badge pm-badge-success">
                                <i data-lucide="eye" style="width:10px;height:10px;"></i> Ya
                            </span>
                            @else
                            <span class="pm-badge pm-badge-neutral">
                                <i data-lucide="eye-off" style="width:10px;height:10px;"></i> Tidak
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($warning->report)
                            <span class="pm-code" style="font-size:0.72rem;">{{ $warning->report->code }}</span>
                            @else
                            <span style="color:var(--pm-text-d);">—</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <div class="pm-action-group">
                                <button wire:click="openForm({{ $warning->id }})"
                                    class="pm-btn pm-btn-outline pm-btn-sm pm-btn-icon" title="Edit">
                                    <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                </button>
                                <button wire:click="toggleStatus({{ $warning->id }})"
                                    class="pm-btn pm-btn-ghost pm-btn-sm pm-btn-icon"
                                    title="{{ $warning->status==='active'?'Nonaktifkan':'Aktifkan' }}">
                                    <i data-lucide="{{ $warning->status==='active'?'pause-circle':'play-circle' }}" style="width:13px;height:13px;"></i>
                                </button>
                                <button wire:click="deleteWarning({{ $warning->id }})"
                                    onclick="return confirm('Yakin hapus peringatan ini?')"
                                    class="pm-btn pm-btn-danger pm-btn-sm pm-btn-icon" title="Hapus">
                                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">
                        <div class="pm-empty">
                            <div class="pm-empty-icon">
                                <i data-lucide="bell-off" style="width:30px;height:30px;"></i>
                            </div>
                            <p>Tidak ada peringatan ditemukan.</p>
                            @if($search || $filterSeverity !== 'all' || $filterStatus !== 'all')
                            <button wire:click="$set('search','');$set('filterSeverity','all');$set('filterStatus','all')"
                                class="pm-btn pm-btn-ghost pm-btn-sm" style="margin-top:0.5rem;">
                                Hapus filter
                            </button>
                            @endif
                        </div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pm-table-footer">
            <span class="pm-count">
                @if($warnings->total() > 0)
                    Menampilkan {{ $warnings->firstItem() }}–{{ $warnings->lastItem() }} dari <strong>{{ $warnings->total() }}</strong> peringatan
                @else
                    Tidak ada hasil
                @endif
            </span>
            @if($warnings->hasPages())
            <div>{{ $warnings->links() }}</div>
            @endif
        </div>
    </div>

</div>

@push('styles')
<style>
.pm-sev-high   { background:rgba(248,113,113,0.14);border:1px solid rgba(248,113,113,0.35);color:#f87171; }
.pm-sev-medium { background:rgba(245,158,11,0.14);border:1px solid rgba(245,158,11,0.35);color:#f59e0b; }
.pm-sev-low    { background:rgba(56,189,248,0.12);border:1px solid rgba(56,189,248,0.3);color:#38bdf8; }
.pm-row-high { border-left: 2px solid rgba(248,113,113,0.4); }
</style>
@endpush

@push('scripts')
<script>
function warnAnim() {
    return {
        init() {
            this.$nextTick(() => {
                this.entrance();
                this.watchLivewire();
            });
        },

        entrance() {
            // Header slide
            const h = document.querySelector('[data-anim="slide-down"]');
            if (h && typeof anime !== 'undefined') {
                h.style.opacity = '0'; h.style.transform = 'translateY(-20px)';
                anime({ targets: h, opacity: [0,1], translateY: ['-20px','0px'], duration: 520, easing: 'easeOutExpo' });
            }

            // Stat cards bounce
            const stats = document.querySelectorAll('#warn-stats .pm-stat');
            if (stats.length && typeof anime !== 'undefined') {
                stats.forEach(s => { s.style.opacity='0'; s.style.transform='scale(0.85) translateY(20px)'; });
                anime({
                    targets: Array.from(stats),
                    opacity: [0,1], scale: [0.85,1], translateY: ['20px','0px'],
                    duration: 500, delay: anime.stagger(80, {start:150}), easing: 'easeOutBack'
                });
            }

            // Toolbar + table fade up
            document.querySelectorAll('[data-anim="fade-up"]').forEach(el => {
                el.style.opacity = '0'; el.style.transform = 'translateY(16px)';
                const delay = parseInt(el.dataset.delay || 0);
                if (typeof anime !== 'undefined') {
                    anime({ targets: el, opacity:[0,1], translateY:['16px','0px'], duration:440, delay, easing:'easeOutCubic' });
                }
            });

            this.animRows();
        },

        animRows() {
            const rows = document.querySelectorAll('#warn-table tbody tr:not([data-rowed])');
            if (!rows.length || typeof anime === 'undefined') return;
            rows.forEach(r => r.dataset.rowed = '1');

            anime({
                targets: Array.from(rows),
                opacity: [0, 1],
                translateX: ['-14px', '0px'],
                duration: 380,
                delay: anime.stagger(45),
                easing: 'easeOutCubic'
            });

            // Severity badges pop
            const sevBadges = document.querySelectorAll('#warn-table .pm-sev-high, #warn-table .pm-sev-medium, #warn-table .pm-sev-low');
            sevBadges.forEach(b => {
                if (b.dataset.popped) return;
                b.dataset.popped = '1';
            });
            anime({
                targets: Array.from(sevBadges),
                scale: [0.5, 1],
                opacity: [0, 1],
                duration: 380,
                delay: anime.stagger(30, {start: 250}),
                easing: 'easeOutElastic(1, 0.6)'
            });
        },

        formIn() {
            const form = document.getElementById('warn-form');
            if (!form || typeof anime === 'undefined') return;
            form.style.opacity = '0'; form.style.transform = 'translateY(-16px) scale(0.98)';
            anime({ targets: form, opacity:[0,1], translateY:['-16px','0px'], scale:[0.98,1], duration:380, easing:'easeOutBack' });
        },

        hoverBtn(el) {
            if (typeof anime !== 'undefined') anime({ targets: el, scale: 1.05, duration: 200, easing: 'easeOutBack' });
        },
        unhoverBtn(el) {
            if (typeof anime !== 'undefined') anime({ targets: el, scale: 1, duration: 200, easing: 'easeOutQuad' });
        },

        watchLivewire() {
            if (typeof Livewire === 'undefined') return;
            Livewire.hook('morph.updated', () => {
                document.querySelectorAll('#warn-table tbody tr').forEach(r => delete r.dataset.rowed);
                document.querySelectorAll('#warn-table .pm-sev-high, #warn-table .pm-sev-medium, #warn-table .pm-sev-low').forEach(b => delete b.dataset.popped);
                setTimeout(() => {
                    this.animRows();
                    if (window.lucide) lucide.createIcons();
                    // If form just appeared
                    const form = document.getElementById('warn-form');
                    if (form && !form.dataset.animated) { form.dataset.animated='1'; this.formIn(); }
                }, 40);
            });
        }
    }
}
</script>
@endpush