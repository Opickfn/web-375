<div x-data="gedungAnim()" x-init="init()">

    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">
                <i data-lucide="building-2" style="width:22px;height:22px;display:inline;vertical-align:middle;margin-right:8px;color:#5588A3;"></i>
                Kelola Lokasi
            </h1>
            <p class="pm-sub">Hirarki: Kampus → Gedung / Infrastruktur → Lantai → Ruangan / Area</p>
        </div>
        <button wire:click="openForm" class="pm-btn pm-btn-primary"
            @mouseenter="hoverBtn($el)" @mouseleave="unhoverBtn($el)">
            <i data-lucide="plus" style="width:15px;height:15px;"></i> Tambah Kampus
        </button>
    </div>

    {{-- Message --}}
    @if($message)
    <div class="pm-flash pm-flash-{{ $messageType==='success'?'success':'danger' }}"
        x-init="if(typeof anime!=='undefined'){$el.style.opacity=0;$el.style.transform='translateY(-10px)';anime({targets:$el,opacity:[0,1],translateY:['-10px','0px'],duration:380,easing:'easeOutBack'})}">
        <i data-lucide="{{ $messageType==='success'?'check-circle':'alert-circle' }}" style="width:15px;height:15px;flex-shrink:0;"></i>
        <span>{{ $message }}</span>
        <button wire:click="$set('message',null)" style="margin-left:auto;background:none;border:none;color:inherit;cursor:pointer;font-size:1.2rem;">×</button>
    </div>
    @endif

    {{-- Add/Edit Form --}}
    @if($showForm)
    <div class="pm-card" style="margin-bottom:1.25rem;" data-anim="form-slide">
        <div class="pm-card-header">
            <h3>{{ $editLocationId ? '✏️ Edit Lokasi' : '➕ Tambah Kampus Baru' }}</h3>
            <button wire:click="closeForm" class="pm-btn pm-btn-ghost pm-btn-icon">
                <i data-lucide="x" style="width:14px;height:14px;"></i>
            </button>
        </div>
        <div class="pm-card-body">
            <form wire:submit.prevent="saveLocation">
                @if($editLocationId)
                <div class="pm-grid-3" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Jenis Lokasi</label>
                        <select wire:model="lType" wire:change="updatedLType" class="pm-select-full">
                            <option value="campus">Kampus</option>
                            <option value="gedung">Gedung</option>
                            <option value="infrastruktur">Infrastruktur Umum</option>
                            <option value="lantai">Lantai</option>
                            <option value="ruangan">Ruangan</option>
                            <option value="area">Area Lainnya</option>
                        </select>
                        @error('lType') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Induk Lokasi</label>
                        <select wire:model="lParentId" class="pm-select-full" {{ $lType==='campus'?'disabled':'' }}>
                            <option value="">— Pilih induk —</option>
                            @foreach($parentOptions as $opt)
                            <option value="{{ $opt->id }}">{{ $opt->label }}</option>
                            @endforeach
                        </select>
                        @error('lParentId') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Kode <span style="color:var(--pm-text-d);font-weight:400;">(opsional)</span></label>
                        <input type="text" wire:model="lCode" class="pm-input" placeholder="Contoh: G1" maxlength="20"
                            style="text-transform:uppercase;">
                        @error('lCode') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="pm-form-group">
                    <label class="pm-label">Nama Lokasi</label>
                    <input type="text" wire:model="lName" class="pm-input" placeholder="Nama lengkap lokasi">
                    @error('lName') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                @else
                {{-- New campus form --}}
                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Nama Kampus</label>
                        <input type="text" wire:model="lName" class="pm-input" placeholder="Contoh: Kampus Utama">
                        @error('lName') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Kode Kampus <span style="color:var(--pm-text-d);font-weight:400;">(opsional)</span></label>
                        <input type="text" wire:model="lCode" class="pm-input" placeholder="KP1" maxlength="20" style="text-transform:uppercase;">
                    </div>
                </div>
                @endif

                <div style="display:flex;gap:0.6rem;">
                    <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled">
                        <i data-lucide="save" style="width:14px;height:14px;"></i>
                        <span wire:loading.remove>{{ $editLocationId ? 'Perbarui' : 'Simpan' }}</span>
                        <span wire:loading>Menyimpan…</span>
                    </button>
                    <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Location Tree --}}
    <div class="pm-card" data-anim="fade-up" data-delay="80">
        <div class="pm-card-header">
            <h3>Daftar Lokasi</h3>
            <span style="font-size:0.75rem;color:var(--pm-text-d);">{{ $locations->total() }} kampus</span>
        </div>

        <div class="pm-card-body" x-data="locationTree()">

            @forelse($locations as $campus)
            <div class="pm-tree-root" wire:key="campus-{{ $campus->id }}">
                {{-- Campus node --}}
                <div class="pm-tree-node pm-tree-campus">
                    <div class="pm-tree-head">
                        <button type="button" @click="toggle('c{{ $campus->id }}')"
                            class="pm-tree-toggle"
                            :class="open['c{{ $campus->id }}'] ? 'pm-tree-toggle-open' : ''">
                            <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                        </button>
                        <div class="pm-tree-icon pm-tree-icon-campus">
                            <i data-lucide="map-pin" style="width:16px;height:16px;"></i>
                        </div>
                        <div class="pm-tree-label">
                            <div style="font-size:0.65rem;font-weight:700;color:rgba(232,232,232,0.3);text-transform:uppercase;letter-spacing:0.1em;">Kampus</div>
                            <div style="font-weight:700;font-size:0.9rem;color:#E8E8E8;">
                                @if($campus->code)<span class="pm-code" style="font-size:0.7rem;margin-right:6px;">{{ $campus->code }}</span>@endif
                                {{ $campus->name }}
                            </div>
                        </div>
                        <div class="pm-tree-actions">
                            <span class="pm-badge pm-badge-accent" style="font-size:0.62rem;">Kampus</span>
                            <button wire:click="openChildForm({{ $campus->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm" title="Tambah sub-lokasi">
                                <i data-lucide="plus" style="width:13px;height:13px;"></i>
                            </button>
                            <button wire:click="openForm({{ $campus->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm" title="Edit">
                                <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                            </button>
                            <button wire:click="toggleLocation({{ $campus->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm"
                                title="{{ $campus->is_active?'Nonaktifkan':'Aktifkan' }}">
                                <i data-lucide="{{ $campus->is_active?'eye-off':'eye' }}" style="width:13px;height:13px;"></i>
                            </button>
                            <button wire:click="deleteLocation({{ $campus->id }})"
                                onclick="return confirm('Hapus kampus {{ $campus->name }} beserta semua sub-lokasinya?')"
                                class="pm-btn pm-btn-danger pm-btn-icon pm-btn-sm" title="Hapus">
                                <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Inline add child form --}}
                    @if($activeAddParentId === $campus->id)
                    <div class="pm-tree-inline-form">
                        <div style="display:flex;gap:0.65rem;align-items:flex-end;flex-wrap:wrap;">
                            <div><label class="pm-label">Jenis</label>
                                <select wire:model="activeAddType" class="pm-select" style="width:180px;">
                                    <option value="">Pilih…</option>
                                    <option value="gedung">Gedung</option>
                                    <option value="infrastruktur">Infrastruktur Umum</option>
                                </select>
                                @error('activeAddType') <p class="pm-form-error">{{ $message }}</p> @enderror
                            </div>
                            <div><label class="pm-label">Nama</label>
                                <input type="text" wire:model="addName" class="pm-input" style="width:200px;" placeholder="Nama lokasi">
                                @error('addName') <p class="pm-form-error">{{ $message }}</p> @enderror
                            </div>
                            <div><label class="pm-label">Kode</label>
                                <input type="text" wire:model="addCode" class="pm-input" style="width:90px;text-transform:uppercase;" placeholder="G1">
                            </div>
                            <button wire:click="submitAddChild" class="pm-btn pm-btn-primary pm-btn-sm">Simpan</button>
                            <button wire:click="cancelAddChild" class="pm-btn pm-btn-ghost pm-btn-sm">Batal</button>
                        </div>
                    </div>
                    @endif

                    {{-- Children --}}
                    <div x-show="open['c{{ $campus->id }}']" x-transition:enter="pm-tree-enter" class="pm-tree-children">
                        @if($campus->children->isEmpty())
                        <div style="padding:0.75rem 1rem;font-size:0.8rem;color:var(--pm-text-d);font-style:italic;">Belum ada sub-lokasi.</div>
                        @endif

                        @foreach($campus->children->take($childDisplayLimit) as $branch)
                        <div class="pm-tree-node pm-tree-branch" wire:key="branch-{{ $branch->id }}" style="{{ !$branch->is_active?'opacity:0.6;':'' }}">
                            <div class="pm-tree-head">
                                <button type="button" @click="toggle('b{{ $branch->id }}')"
                                    class="pm-tree-toggle" :class="open['b{{ $branch->id }}']?'pm-tree-toggle-open':''">
                                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                                </button>
                                <div class="pm-tree-icon pm-tree-icon-branch">
                                    <i data-lucide="{{ $branch->type==='gedung'?'building':'landmark' }}" style="width:14px;height:14px;"></i>
                                </div>
                                <div class="pm-tree-label">
                                    <div style="font-weight:600;font-size:0.85rem;color:#E8E8E8;">
                                        @if($branch->code)<span class="pm-code" style="font-size:0.68rem;margin-right:5px;">{{ $branch->code }}</span>@endif
                                        {{ $branch->name }}
                                    </div>
                                    <div style="font-size:0.7rem;color:var(--pm-text-d);">{{ $branch->type==='gedung'?'Gedung':'Infrastruktur Umum' }}</div>
                                </div>
                        <div class="pm-tree-actions">
                                    @if($branch->type==='gedung')
                                    <button wire:click="openChildForm({{ $branch->id }},'lantai')" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm" title="Tambah lantai">
                                        <i data-lucide="plus" style="width:12px;height:12px;"></i>
                                    </button>
                                    @endif
                                    <button wire:click="openForm({{ $branch->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm">
                                        <i data-lucide="pencil" style="width:12px;height:12px;"></i>
                                    </button>
                                    <button wire:click="toggleLocation({{ $branch->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm">
                                        <i data-lucide="{{ $branch->is_active?'eye-off':'eye' }}" style="width:12px;height:12px;"></i>
                                    </button>
                                    <button wire:click="deleteLocation({{ $branch->id }})" onclick="return confirm('Hapus {{ $branch->name }} dan sub-lokasinya?')" class="pm-btn pm-btn-danger pm-btn-icon pm-btn-sm" title="Hapus">
                                        <i data-lucide="trash-2" style="width:12px;height:12px;"></i>
                                    </button>
                                </div>
                            </div>

                            @if($activeAddParentId === $branch->id)
                            <div class="pm-tree-inline-form">
                                <div style="display:flex;gap:0.65rem;align-items:flex-end;flex-wrap:wrap;">
                                    <div><label class="pm-label">Nama Lantai</label><input type="text" wire:model="addName" class="pm-input" style="width:180px;" placeholder="Lantai 1"></div>
                                    <div><label class="pm-label">Kode</label><input type="text" wire:model="addCode" class="pm-input" style="width:80px;text-transform:uppercase;" placeholder="L1"></div>
                                    <button wire:click="submitAddChild" class="pm-btn pm-btn-primary pm-btn-sm">Simpan</button>
                                    <button wire:click="cancelAddChild" class="pm-btn pm-btn-ghost pm-btn-sm">Batal</button>
                                </div>
                            </div>
                            @endif

                            {{-- Floors --}}
                            <div x-show="open['b{{ $branch->id }}']" x-transition:enter="pm-tree-enter" class="pm-tree-children">
                                @foreach($branch->children->take($childDisplayLimit) as $floor)
                                <div class="pm-tree-node pm-tree-floor" wire:key="floor-{{ $floor->id }}" style="{{ !$floor->is_active?'opacity:0.6;':'' }}">
                                    <div class="pm-tree-head">
                                        <button type="button" @click="toggle('f{{ $floor->id }}')"
                                            class="pm-tree-toggle" :class="open['f{{ $floor->id }}']?'pm-tree-toggle-open':''">
                                            <i data-lucide="chevron-right" style="width:11px;height:11px;"></i>
                                        </button>
                                        <div class="pm-tree-icon pm-tree-icon-floor">
                                            <i data-lucide="layers" style="width:13px;height:13px;"></i>
                                        </div>
                                        <div class="pm-tree-label">
                                            <div style="font-weight:600;font-size:0.82rem;color:#E8E8E8;">
                                                @if($floor->code)<span class="pm-code" style="font-size:0.65rem;margin-right:4px;">{{ $floor->code }}</span>@endif
                                                {{ $floor->name }}
                                            </div>
                                        </div>
                                        <div class="pm-tree-actions">
                                            <button wire:click="openChildForm({{ $floor->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm" title="Tambah ruangan">
                                                <i data-lucide="plus" style="width:11px;height:11px;"></i>
                                            </button>
                                            <button wire:click="openForm({{ $floor->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm">
                                                <i data-lucide="pencil" style="width:11px;height:11px;"></i>
                                            </button>
                                            <button wire:click="toggleLocation({{ $floor->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm" title="{{ $floor->is_active?'Nonaktifkan':'Aktifkan' }}">
                                                <i data-lucide="{{ $floor->is_active?'eye-off':'eye' }}" style="width:11px;height:11px;"></i>
                                            </button>
                                            <button wire:click="deleteLocation({{ $floor->id }})" onclick="return confirm('Hapus {{ $floor->name }} dan ruangannya?')" class="pm-btn pm-btn-danger pm-btn-icon pm-btn-sm" title="Hapus">
                                                <i data-lucide="trash-2" style="width:11px;height:11px;"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @if($activeAddParentId === $floor->id)
                                    <div class="pm-tree-inline-form">
                                        <div style="display:flex;gap:0.65rem;align-items:flex-end;flex-wrap:wrap;">
                                            <div><label class="pm-label">Jenis</label>
                                                <select wire:model="activeAddType" class="pm-select" style="width:160px;">
                                                    <option value="">Pilih…</option>
                                                    <option value="ruangan">Ruangan</option>
                                                    <option value="area">Area Lainnya</option>
                                                </select>
                                            </div>
                                            <div><label class="pm-label">Nama</label><input type="text" wire:model="addName" class="pm-input" style="width:180px;" placeholder="Nama ruangan"></div>
                                            <div><label class="pm-label">Kode</label><input type="text" wire:model="addCode" class="pm-input" style="width:80px;text-transform:uppercase;" placeholder="R101"></div>
                                            <button wire:click="submitAddChild" class="pm-btn pm-btn-primary pm-btn-sm">Simpan</button>
                                            <button wire:click="cancelAddChild" class="pm-btn pm-btn-ghost pm-btn-sm">Batal</button>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Spaces --}}
                                    <div x-show="open['f{{ $floor->id }}']" x-transition:enter="pm-tree-enter" class="pm-tree-children">
                                        @foreach($floor->children->take($childDisplayLimit) as $space)
                                        <div class="pm-tree-node pm-tree-space" wire:key="space-{{ $space->id }}" style="{{ !$space->is_active?'opacity:0.5;':'' }}">
                                            <div class="pm-tree-head" style="padding:0.6rem 1rem;">
                                                <div class="pm-tree-icon pm-tree-icon-space">
                                                    <i data-lucide="{{ $space->type==='area'?'grid':'door-open' }}" style="width:12px;height:12px;"></i>
                                                </div>
                                                <div class="pm-tree-label">
                                                    <div style="font-size:0.8rem;font-weight:600;color:#E8E8E8;">
                                                        @if($space->code)<span class="pm-code" style="font-size:0.63rem;margin-right:4px;">{{ $space->code }}</span>@endif
                                                        {{ $space->name }}
                                                    </div>
                                                    <div style="font-size:0.68rem;color:var(--pm-text-d);">{{ $space->type==='area'?'Area Lainnya':'Ruangan' }}</div>
                                                </div>
                                                <div class="pm-tree-actions">
                                                    <button wire:click="openForm({{ $space->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm">
                                                        <i data-lucide="pencil" style="width:11px;height:11px;"></i>
                                                    </button>
                                                    <button wire:click="toggleLocation({{ $space->id }})" class="pm-btn pm-btn-ghost pm-btn-icon pm-btn-sm" title="{{ $space->is_active?'Nonaktifkan':'Aktifkan' }}">
                                                        <i data-lucide="{{ $space->is_active?'eye-off':'eye' }}" style="width:11px;height:11px;"></i>
                                                    </button>
                                                    <button wire:click="deleteLocation({{ $space->id }})" onclick="return confirm('Hapus {{ $space->name }}?')" class="pm-btn pm-btn-danger pm-btn-icon pm-btn-sm" title="Hapus">
                                                        <i data-lucide="trash-2" style="width:11px;height:11px;"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if($floor->children->count() > $childDisplayLimit)
                                        <div style="padding:0.5rem 1rem;font-size:0.73rem;color:var(--pm-text-d);">
                                            +{{ $floor->children->count() - $childDisplayLimit }} ruangan/area lainnya
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                        @if($campus->children->count() > $childDisplayLimit)
                        <div style="padding:0.5rem 1rem;font-size:0.75rem;color:var(--pm-text-d);">
                            +{{ $campus->children->count() - $childDisplayLimit }} gedung/infrastruktur lainnya
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @empty
            <div class="pm-empty">
                <div class="pm-empty-icon"><i data-lucide="map-pin" style="width:30px;height:30px;"></i></div>
                <p>Belum ada data lokasi. Tambahkan kampus pertama.</p>
            </div>
            @endforelse

        </div>

        @if($locations->hasPages())
        <div class="pm-card-footer">{{ $locations->links() }}</div>
        @endif
    </div>

</div>

@push('styles')
<style>
/* Tree node styles */
.pm-tree-root { margin-bottom: 0.85rem; }
.pm-tree-node {
    background: rgba(0,40,65,0.5);
    border: 1px solid rgba(20,83,116,0.4);
    border-radius: 12px;
    margin-bottom: 0.4rem;
    transition: background 0.2s ease, border-color 0.2s ease;
}
.pm-tree-node:hover { background: rgba(85,136,163,0.07); border-color: rgba(85,136,163,0.3); }
.pm-tree-campus { border-left: 3px solid rgba(85,136,163,0.6); }
.pm-tree-branch { border-left: 3px solid rgba(245,158,11,0.4); }
.pm-tree-floor  { border-left: 3px solid rgba(74,222,128,0.3); }
.pm-tree-space  { border-left: 2px solid rgba(56,189,248,0.2); }

.pm-tree-head { display:flex; align-items:center; gap:0.6rem; padding:0.8rem 1rem; cursor:pointer; }
.pm-tree-toggle {
    display:flex; align-items:center; justify-content:center;
    width:22px; height:22px; border-radius:6px;
    background: rgba(85,136,163,0.12); border: 1px solid rgba(20,83,116,0.4);
    color: var(--pm-text-d); cursor:pointer;
    transition: transform 0.2s ease, background 0.2s ease;
}
.pm-tree-toggle-open { transform: rotate(90deg); background: rgba(85,136,163,0.2); color: #5588A3; }
.pm-tree-toggle:hover { background: rgba(85,136,163,0.2); }

.pm-tree-icon { width:32px; height:32px; flex-shrink:0; border-radius:8px; display:flex; align-items:center; justify-content:center; }
.pm-tree-icon-campus { background:rgba(85,136,163,0.15); color:#5588A3; }
.pm-tree-icon-branch { background:rgba(245,158,11,0.12); color:#f59e0b; }
.pm-tree-icon-floor  { background:rgba(74,222,128,0.1); color:#4ade80; }
.pm-tree-icon-space  { background:rgba(56,189,248,0.1); color:#38bdf8; }

.pm-tree-label { flex:1; min-width:0; }
.pm-tree-actions { display:flex; gap:0.3rem; margin-left:auto; }
.pm-tree-children { border-left:2px solid rgba(20,83,116,0.3); margin-left:1.6rem; padding-left:0.75rem; padding-top:0.3rem; padding-bottom:0.3rem; }

.pm-tree-inline-form {
    margin:0.4rem 1rem 0.6rem;
    padding:0.85rem 1rem;
    background: rgba(85,136,163,0.07);
    border: 1px dashed rgba(85,136,163,0.35);
    border-radius:10px;
}
[x-transition\:enter] { transition: all 0.25s ease; }
</style>
@endpush

@push('scripts')
<script>
function locationTree() {
    return {
        open: {},
        toggle(k) { this.open[k] = !this.open[k]; }
    };
}

function gedungAnim() {
    return {
        init() {
            this.$nextTick(() => {
                const h = document.querySelector('[data-anim="slide-down"]');
                if (h && typeof anime !== 'undefined') {
                    h.style.opacity='0'; h.style.transform='translateY(-20px)';
                    anime({ targets:h, opacity:[0,1], translateY:['-20px','0px'], duration:520, easing:'easeOutExpo' });
                }
                document.querySelectorAll('[data-anim="fade-up"]').forEach(el => {
                    el.style.opacity='0'; el.style.transform='translateY(16px)';
                    anime({ targets:el, opacity:[0,1], translateY:['16px','0px'], duration:440, delay:parseInt(el.dataset.delay||0), easing:'easeOutCubic' });
                });

                // Tree nodes stagger
                const nodes = document.querySelectorAll('.pm-tree-root');
                nodes.forEach(n => { n.style.opacity='0'; n.style.transform='translateX(-12px)'; });
                if (nodes.length && typeof anime !== 'undefined') {
                    anime({ targets:Array.from(nodes), opacity:[0,1], translateX:['-12px','0px'], duration:420, delay:anime.stagger(60,{start:200}), easing:'easeOutCubic' });
                }

                if (typeof Livewire !== 'undefined') {
                    Livewire.hook('morph.updated', () => {
                        setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 30);
                    });
                }
            });
        },
        hoverBtn(el) {
            if (typeof anime !== 'undefined') anime({ targets:el, scale:1.05, duration:180, easing:'easeOutBack' });
        },
        unhoverBtn(el) {
            if (typeof anime !== 'undefined') anime({ targets:el, scale:1, duration:180, easing:'easeOutQuad' });
        }
    }
}
</script>
@endpush