<div>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1>Periode Reward</h1>
            <p>Kelola periode untuk rekap poin dan pemberian reward</p>
        </div>
        <button wire:click="openForm" class="btn btn-primary">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Buat Periode
        </button>
    </div>

    @if($showForm)
    <div class="card mb-4 animate-in">
        <div class="card-header"><h3>Buat Periode Baru</h3></div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="grid grid-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nama Periode</label>
                        <input type="text" wire:model="name" class="form-input" placeholder="Contoh: Semester 1 2026">
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" wire:model="startDate" class="form-input">
                        @error('startDate') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" wire:model="endDate" class="form-input">
                        @error('endDate') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" wire:click="closeForm" class="btn btn-outline">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="grid grid-2 gap-4">
        @forelse($periods as $period)
        <div class="card">
            <div class="card-body">
                <div class="flex justify-between items-center mb-2">
                    <h4>{{ $period->name }}</h4>
                    <span class="badge {{ $period->isActive() ? 'badge-success' : 'badge-neutral' }}">
                        {{ $period->isActive() ? 'Aktif' : 'Ditutup' }}
                    </span>
                </div>
                <p class="text-sm text-muted mb-4">
                    {{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }}
                </p>
                @if($period->isActive())
                <button wire:click="closePeriod({{ $period->id }})" class="btn btn-outline btn-sm" wire:confirm="Tutup periode ini?">
                    <i data-lucide="lock" style="width:14px;height:14px;"></i> Tutup Periode
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="card" style="grid-column:span 2;">
            <div class="card-body">
                <div class="empty-state">
                    <i data-lucide="gift" style="width:40px;height:40px;"></i>
                    <p>Belum ada periode reward.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
