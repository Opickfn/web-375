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
        <div class="card-header flex items-center justify-between gap-3 rounded-t-3xl bg-slate-50 px-5 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold">Daftar Lokasi</h3>
            <button wire:click="openForm" class="btn btn-primary btn-sm">
                <i data-lucide="plus" style="width:14px;height:14px;"></i> Tambah Kampus
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
            <div x-data="locationTree()" x-cloak class="space-y-5 rounded-3xl bg-slate-100 border border-slate-200 px-4 py-5">
                @forelse($locations as $location)
                    <div class="card card-bordered border-l-4 border-red-500 mb-4 {{ $location->is_active ? '' : 'opacity-60' }}">
                        <div class="card-body p-0">
                            <div class="relative flex items-center gap-2 px-4 py-4">
                                <button type="button" @click="toggle('loc-{{ $location->id }}')" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 focus:outline-none" :class="open['loc-{{ $location->id }}'] ? 'rotate-90' : ''">
                                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                                </button>
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                                <i data-lucide="layers" class="h-4 w-4"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-500">Kampus</div>
                                <h3 class="truncate text-sm font-semibold text-slate-900">{{ $location->name }}</h3>
                            </div>
                            <div class="ml-auto flex flex-wrap items-center gap-1">
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-600">Kampus</span>
                                <button wire:click="openChildForm({{ $location->id }})" class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-50">
                                    <i data-lucide="plus" class="h-3.5 w-3.5"></i> Tambah
                                </button>
                                <button wire:click="openForm({{ $location->id }})" class="inline-flex h-8 items-center justify-center rounded-full border border-slate-200 bg-white px-2.5 text-slate-600 hover:bg-slate-50">
                                    <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                </button>
                                <button wire:click="toggleLocation({{ $location->id }})" class="inline-flex h-8 items-center justify-center rounded-full border border-slate-200 bg-white px-2.5 text-slate-600 hover:bg-slate-50" title="{{ $location->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i data-lucide="{{ $location->is_active ? 'eye-off' : 'eye' }}" class="h-3.5 w-3.5"></i>
                                </button>
                                <button wire:click="deleteLocation({{ $location->id }})" onclick="return confirm('Yakin hapus lokasi ini? Tindakan tidak dapat dibatalkan.')" class="inline-flex h-8 items-center justify-center rounded-full border border-slate-200 bg-white px-2.5 text-slate-600 hover:bg-slate-50" title="Hapus">
                                    <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                </button>
                            </div>
                        </div>

                        @if($activeAddParentId === $location->id)
                            <div class="border-t border-slate-100 bg-slate-50 px-3 py-3">
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

                        <div x-show="open['loc-{{ $location->id }}']" x-transition class="border-t border-slate-100 px-3 pb-3 pt-2">
                            @if($location->children->isEmpty())
                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-3 text-sm text-slate-500">Belum ada sub-lokasi.</div>
                            @endif

                            <div class="space-y-2">
                                @foreach($location->children as $branch)
                                    <div class="card card-bordered border-l-4 border-amber-400 mb-3 {{ $branch->is_active ? '' : 'opacity-60' }}">
                                        <div class="card-body p-3">
                                            <div class="relative flex items-center gap-2 px-4 py-3">
                                                <button type="button" @click="toggle('branch-{{ $branch->id }}')" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 focus:outline-none" :class="open['branch-{{ $branch->id }}'] ? 'rotate-90' : ''">
                                                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                                                </button>
                                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                                                <i data-lucide="building" class="h-4 w-4"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $branch->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $branch->type === 'gedung' ? 'Gedung' : 'Infrastruktur' }}</p>
                                            </div>
                                            <div class="ml-auto flex flex-wrap items-center gap-1">
                                                <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] text-slate-600">{{ $branch->type === 'gedung' ? 'Gedung' : 'Infrastruktur' }}</span>
                                                @if($branch->type === 'gedung')
                                                    <button wire:click="openChildForm({{ $branch->id }}, 'lantai')" class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-50">
                                                        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                                                    </button>
                                                @endif
                                                <button wire:click="openForm({{ $branch->id }})" class="inline-flex h-8 items-center justify-center rounded-full border border-slate-200 bg-white px-2.5 text-slate-600 hover:bg-slate-50">
                                                    <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                                </button>
                                            </div>
                                        </div>

                                        @if($activeAddParentId === $branch->id)
                                            <div class="mt-3 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-3">
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

                                        <div x-show="open['branch-{{ $branch->id }}']" x-transition class="mt-2 border-l border-slate-200 pl-5">
                                            @if($branch->children->isEmpty())
                                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-3 text-sm text-slate-500">Belum ada lantai.</div>
                                            @endif

                                            <div class="space-y-2">
                                                @foreach($branch->children as $floor)
                                                    <div class="card card-bordered border-l-4 border-emerald-400 mb-3 {{ $floor->is_active ? '' : 'opacity-60' }}">
                                                        <div class="card-body p-3">
                                                            <div class="relative flex items-center gap-2 px-4 py-3">
                                                                <button type="button" @click="toggle('floor-{{ $floor->id }}')" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 focus:outline-none" :class="open['floor-{{ $floor->id }}'] ? 'rotate-90' : ''">
                                                                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                                                                </button>
                                                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                                                                <i data-lucide="home" class="h-4 w-4"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $floor->name }}</p>
                                                                <p class="text-xs text-slate-500">Lantai</p>
                                                            </div>
                                                            <div class="ml-auto flex flex-wrap items-center gap-1">
                                                                <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] text-slate-600">Lantai</span>
                                                                <button wire:click="openChildForm({{ $floor->id }})" class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-50">
                                                                    <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                                                                </button>
                                                                <button wire:click="openForm({{ $floor->id }})" class="inline-flex h-8 items-center justify-center rounded-full border border-slate-200 bg-white px-2.5 text-slate-600 hover:bg-slate-50">
                                                                    <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        @if($activeAddParentId === $floor->id)
                                                            <div class="mt-3 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-3">
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

                                                        <div x-show="open['floor-{{ $floor->id }}']" x-transition class="mt-2 border-l border-slate-200 pl-5">
                                                            @if($floor->children->isEmpty())
                                                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-3 text-sm text-slate-500">Belum ada ruang atau area.</div>
                                                            @endif
                                                            <div class="space-y-2">
                                                                @foreach($floor->children as $space)
                                                                    <div class="card card-bordered border-l-4 border-emerald-200 mb-3 {{ $space->is_active ? '' : 'opacity-60' }}">
                                                                        <div class="card-body flex flex-col gap-3 p-3 md:flex-row md:items-center md:justify-between">
                                                                            <div>
                                                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $space->name }}</p>
                                                                                <p class="text-xs text-slate-500">{{ $space->type === 'area' ? 'Area Lainnya' : 'Ruangan' }}</p>
                                                                            </div>
                                                                        <div class="flex flex-wrap items-center gap-1">
                                                                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] text-slate-600">{{ ucfirst($space->type) }}</span>
                                                                            <button wire:click="openForm({{ $space->id }})" class="inline-flex h-8 items-center justify-center rounded-full border border-slate-200 bg-white px-2.5 text-slate-600 hover:bg-slate-50">
                                                                                <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">
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
