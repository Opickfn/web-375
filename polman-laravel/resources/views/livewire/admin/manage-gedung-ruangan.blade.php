<div>
    <div class="page-header">
        <h1>Kelola Lokasi</h1>
        <p>Atur lokasi kampus dalam hirarki Kampus > Gedung/Infrastruktur > Lantai > Ruangan/Area.</p>
    </div>

    {{-- Message Alert --}}
    @if($message)
    <div class="alert alert-{{ $messageType }} alert-dismissible animate-in mb-6" role="alert">
        <div class="flex items-center gap-3">
            @if($messageType === 'success')
                <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
            @else
                <i data-lucide="alert-circle" style="width:20px;height:20px;"></i>
            @endif
            <span>{{ $message }}</span>
        </div>
        <button type="button" wire:click="$set('message', null)" class="btn-close" aria-label="Close"></button>
    </div>
    @endif

    <div class="card card-bordered mb-6">
        <div class="card-header flex items-center justify-between gap-3 rounded-t-3xl bg-slate-950/70 px-5 py-4 border-b border-white/5">
            <h3 class="text-lg font-semibold text-white">Daftar Lokasi</h3>
            <button wire:click="openForm" class="btn btn-primary btn-sm btn-dashboard-action btn-plus-glow" title="Tambah Kampus">
                <i data-lucide="plus" style="width:14px;height:14px;"></i>
            </button>
        </div>

        @if($showForm)
        <div class="card-body border-b border-slate-200">
            <form wire:submit.prevent="saveLocation" class="animate-in">
                @if($editLocationId)
                    <div class="grid grid-3 gap-4">
                        <div class="form-group">
                            <label class="form-label">Jenis Lokasi</label>
                            <select wire:model="lType" class="form-select">
                                <option value="campus">Kampus</option>
                                <option value="gedung">Gedung</option>
                                <option value="infrastruktur">Infrastruktur Umum</option>
                                <option value="lantai">Lantai</option>
                                <option value="ruangan">Ruangan</option>
                                <option value="area">Area Lainnya</option>
                            </select>
                            @error('lType') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Induk Lokasi</label>
                            <select wire:model="lParentId" class="form-select" {{ $lType === 'campus' ? 'disabled' : '' }}>
                                <option value="">Pilih lokasi induk</option>
                                @foreach($parentOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->label }}</option>
                                @endforeach
                            </select>
                            @error('lParentId') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kode Lokasi <span class="text-muted">opsional</span></label>
                            <input type="text" wire:model="lCode" class="form-input" placeholder="Contoh: G1" maxlength="20" style="text-transform:uppercase;">
                            @error('lCode') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-2 gap-4 mt-2">
                        <div class="form-group">
                            <label class="form-label">Nama Lokasi</label>
                            <input type="text" wire:model="lName" class="form-input" placeholder="Contoh: Gedung Utama">
                            @error('lName') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-input" value="{{ $lType === 'campus' ? 'Kampus' : ($lType === 'gedung' ? 'Gedung' : ($lType === 'infrastruktur' ? 'Infrastruktur Umum' : ($lType === 'ruangan' ? 'Ruangan' : ($lType === 'area' ? 'Area Lainnya' : '')) )) }}" disabled>
                        </div>
                    </div>
                @else
                    <input type="hidden" wire:model="lType" value="campus">
                    <div class="grid grid-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Nama Kampus</label>
                            <input type="text" wire:model="lName" class="form-input" placeholder="Contoh: Kampus Utama">
                            @error('lName') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kode Kampus <span class="text-muted">opsional</span></label>
                            <input type="text" wire:model="lCode" class="form-input" placeholder="Contoh: K1" maxlength="20" style="text-transform:uppercase;">
                            @error('lCode') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif

                <div class="flex gap-3 mt-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="save" style="width:14px;height:14px;"></i>
                        {{ $editLocationId ? 'Perbarui' : 'Simpan' }}
                    </button>
                    <button type="button" wire:click="closeForm" class="btn btn-outline btn-sm">Batal</button>
                </div>
            </form>
        </div>
        @endif

        <div class="card-body px-0">
            <div x-data="locationTree()" x-cloak class="tree-panel">
                @forelse($locations as $location)
                    <div class="tree-node border-l-4 border-red-500 mb-4 {{ $location->is_active ? '' : 'opacity-70' }}">
                        <div class="tree-node-head">
                            <button type="button" @click="toggle('loc-{{ $location->id }}')" class="tree-toggle-btn" :class="open['loc-{{ $location->id }}'] ? 'rotate-90' : ''" title="Perluas / Tutup Kampus">
                                <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                            </button>
                            <div class="tree-icon">
                                <i data-lucide="layers" class="h-4 w-4"></i>
                            </div>
                            <div class="tree-node-label">
                                <div class="text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-400">Kampus</div>
                                <h3>{{ $location->name }}</h3>
                            </div>
                            <div class="tree-node-actions">
                                <span class="tree-status-pill">Kampus</span>
                                <button wire:click="openChildForm({{ $location->id }})" class="tree-action-btn" title="Tambah Gedung">
                                    <i data-lucide="plus" class="h-4 w-4"></i>
                                </button>
                                <button wire:click="openForm({{ $location->id }})" class="tree-action-btn" title="Edit Lokasi">
                                    <i data-lucide="pencil" class="h-4 w-4"></i>
                                </button>
                                <button wire:click="toggleLocation({{ $location->id }})" class="tree-action-btn" title="{{ $location->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i data-lucide="{{ $location->is_active ? 'eye-off' : 'eye' }}" class="h-4 w-4"></i>
                                </button>
                                <button wire:click="deleteLocation({{ $location->id }})" onclick="return confirm('Yakin hapus lokasi ini? Tindakan tidak dapat dibatalkan.')" class="tree-action-btn" title="Hapus Lokasi">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>

                        @if($activeAddParentId === $location->id)
                            <div class="tree-add-panel">
                                <div class="grid gap-3 md:grid-cols-3">
                                    <div>
                                        <label class="form-label">Jenis Sub-Lokasi</label>
                                        <select wire:model="activeAddType" class="form-select">
                                            <option value="">Pilih jenis</option>
                                            <option value="gedung">Gedung</option>
                                            <option value="infrastruktur">Infrastruktur Umum</option>
                                        </select>
                                        @error('activeAddType') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label">Nama</label>
                                        <input type="text" wire:model="addName" class="form-input" placeholder="Nama sub-lokasi">
                                        @error('addName') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label">Kode (opsional)</label>
                                        <input type="text" wire:model="addCode" class="form-input" placeholder="Contoh: G1" style="text-transform:uppercase;">
                                        @error('addCode') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button wire:click="submitAddChild" class="btn btn-primary btn-sm">Simpan</button>
                                    <button wire:click="cancelAddChild" class="btn btn-outline btn-sm">Batal</button>
                                </div>
                            </div>
                        @endif

                        <div x-show="open['loc-{{ $location->id }}']" x-transition class="tree-node-children">
                            @if($location->children->isEmpty())
                                <div class="tree-empty-state">Belum ada sub-lokasi.</div>
                            @endif

                            <div class="space-y-3">
                                @foreach($location->children->take($childDisplayLimit) as $branch)
                                    <div class="tree-node tree-level border-l-4 border-amber-400 mb-3 {{ $branch->is_active ? '' : 'opacity-70' }}">
                                        <div class="tree-node-head">
                                            <button type="button" @click="toggle('branch-{{ $branch->id }}')" class="tree-toggle-btn" :class="open['branch-{{ $branch->id }}'] ? 'rotate-90' : ''" title="Perluas / Tutup {{ $branch->type === 'gedung' ? 'Gedung' : 'Infrastruktur' }}">
                                                <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                                            </button>
                                            <div class="tree-icon">
                                                <i data-lucide="building" class="h-4 w-4"></i>
                                            </div>
                                            <div class="tree-node-label">
                                                <h3>{{ $branch->name }}</h3>
                                                <p>{{ $branch->type === 'gedung' ? 'Gedung' : 'Infrastruktur' }}</p>
                                            </div>
                                            <div class="tree-node-actions">
                                                <span class="tree-status-pill">{{ $branch->type === 'gedung' ? 'Gedung' : 'Infrastruktur' }}</span>
                                                @if($branch->type === 'gedung')
                                                    <button wire:click="openChildForm({{ $branch->id }}, 'lantai')" class="tree-action-btn" title="Tambah Lantai">
                                                        <i data-lucide="plus" class="h-4 w-4"></i>
                                                    </button>
                                                @endif
                                                <button wire:click="openForm({{ $branch->id }})" class="tree-action-btn" title="Edit Gedung">
                                                    <i data-lucide="pencil" class="h-4 w-4"></i>
                                                </button>
                                            </div>
                                        </div>

                                        @if($activeAddParentId === $branch->id)
                                            <div class="tree-add-panel">
                                                <div class="grid gap-3 md:grid-cols-3">
                                                    <div>
                                                        <label class="form-label">Nama Lantai</label>
                                                        <input type="text" wire:model="addName" class="form-input" placeholder="Nama lantai">
                                                        @error('addName') <p class="form-error">{{ $message }}</p> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Kode (opsional)</label>
                                                        <input type="text" wire:model="addCode" class="form-input" placeholder="Contoh: L1" style="text-transform:uppercase;">
                                                        @error('addCode') <p class="form-error">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    <button wire:click="submitAddChild" class="btn btn-primary btn-sm">Simpan</button>
                                                    <button wire:click="cancelAddChild" class="btn btn-outline btn-sm">Batal</button>
                                                </div>
                                            </div>
                                        @endif

                                        <div x-show="open['branch-{{ $branch->id }}']" x-transition class="tree-node-children">
                                            @if($branch->children->isEmpty())
                                                <div class="tree-empty-state">Belum ada lantai.</div>
                                            @endif

                                            <div class="space-y-2">
                                                @foreach($branch->children->take($childDisplayLimit) as $floor)
                                                    <div class="tree-node tree-level border-l-4 border-emerald-400 mb-3 {{ $floor->is_active ? '' : 'opacity-70' }}">
                                                        <div class="tree-node-head">
                                                            <button type="button" @click="toggle('floor-{{ $floor->id }}')" class="tree-toggle-btn" :class="open['floor-{{ $floor->id }}'] ? 'rotate-90' : ''" title="Perluas / Tutup Lantai">
                                                                <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                                                            </button>
                                                            <div class="tree-icon">
                                                                <i data-lucide="home" class="h-4 w-4"></i>
                                                            </div>
                                                            <div class="tree-node-label">
                                                                <h3>{{ $floor->name }}</h3>
                                                                <p>Lantai</p>
                                                            </div>
                                                            <div class="tree-node-actions">
                                                                <span class="tree-status-pill">Lantai</span>
                                                                <button wire:click="openChildForm({{ $floor->id }})" class="tree-action-btn" title="Tambah Ruangan/Area">
                                                                    <i data-lucide="plus" class="h-4 w-4"></i>
                                                                </button>
                                                                <button wire:click="openForm({{ $floor->id }})" class="tree-action-btn" title="Edit Lantai">
                                                                    <i data-lucide="pencil" class="h-4 w-4"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        @if($activeAddParentId === $floor->id)
                                                            <div class="tree-add-panel">
                                                                <div class="grid gap-3 md:grid-cols-3">
                                                                    <div>
                                                                        <label class="form-label">Jenis</label>
                                                                        <select wire:model="activeAddType" class="form-select">
                                                                            <option value="">Pilih</option>
                                                                            <option value="ruangan">Ruangan</option>
                                                                            <option value="area">Area</option>
                                                                        </select>
                                                                        @error('activeAddType') <p class="form-error">{{ $message }}</p> @enderror
                                                                    </div>
                                                                    <div>
                                                                        <label class="form-label">Nama</label>
                                                                        <input type="text" wire:model="addName" class="form-input" placeholder="Nama ruang atau area">
                                                                        @error('addName') <p class="form-error">{{ $message }}</p> @enderror
                                                                    </div>
                                                                    <div>
                                                                        <label class="form-label">Kode (opsional)</label>
                                                                        <input type="text" wire:model="addCode" class="form-input" placeholder="Contoh: R101" style="text-transform:uppercase;">
                                                                        @error('addCode') <p class="form-error">{{ $message }}</p> @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="mt-3 flex flex-wrap gap-2">
                                                                    <button wire:click="submitAddChild" class="btn btn-primary btn-sm">Simpan</button>
                                                                    <button wire:click="cancelAddChild" class="btn btn-outline btn-sm">Batal</button>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div x-show="open['floor-{{ $floor->id }}']" x-transition class="tree-node-children">
                                                            @if($floor->children->isEmpty())
                                                                <div class="tree-empty-state">Belum ada ruang atau area.</div>
                                                            @endif
                                                            <div class="space-y-2">
                                                                @foreach($floor->children->take($childDisplayLimit) as $space)
                                                                    <div class="tree-node tree-leaf border-l-4 border-emerald-200 mb-3 {{ $space->is_active ? '' : 'opacity-70' }}">
                                                                        <div class="tree-node-head">
                                                                            <div class="tree-icon">
                                                                                <i data-lucide="square" class="h-4 w-4"></i>
                                                                            </div>
                                                                            <div class="tree-node-label">
                                                                                <h3>{{ $space->name }}</h3>
                                                                                <p>{{ $space->type === 'area' ? 'Area Lainnya' : 'Ruangan' }}</p>
                                                                            </div>
                                                                            <div class="tree-node-actions">
                                                                                <span class="tree-status-pill">{{ ucfirst($space->type) }}</span>
                                                                                <button wire:click="openForm({{ $space->id }})" class="tree-action-btn" title="Edit Lokasi">
                                                                                    <i data-lucide="pencil" class="h-4 w-4"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            @if($floor->children->count() > $childDisplayLimit)
                                                                <div class="text-sm text-slate-400 mt-2 ml-12">
                                                                    Menampilkan {{ $childDisplayLimit }} dari {{ $floor->children->count() }} ruang/area pertama.
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if($branch->children->count() > $childDisplayLimit)
                                                    <div class="text-sm text-slate-400 mt-2 ml-12">
                                                        Menampilkan {{ $childDisplayLimit }} dari {{ $branch->children->count() }} lantai pertama.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @if($locations->hasPages())
                    <div class="card-footer flex justify-end pt-4">{{ $locations->links() }}</div>
                @endif
                @empty
                    <div class="empty-state">
                        <i data-lucide="map-pin" class="w-10 h-10 mx-auto mb-3"></i>
                        Belum ada data lokasi.
                    </div>
                @endforelse
            </div>

            <script>
                function locationTree() {
                    return {
                        open: {},
                        toggle(key) {
                            this.open[key] = !this.open[key];
                        },
                    };
                }
            </script>
        </div>
    </div>

    @if(! $showForm)
    <div style="text-align:center;padding:12px 0;">
        <button wire:click="openForm" class="btn btn-outline">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Tambah Kampus Baru
        </button>
    </div>
    @endif
</div>
