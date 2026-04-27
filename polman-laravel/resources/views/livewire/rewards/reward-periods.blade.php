<div x-data="rewardAnim()" x-init="init()">
    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Periode Reward</h1>
            <p class="pm-sub">Kelola periode rekap poin dan pemberian penghargaan</p>
        </div>
        <button wire:click="openForm" class="pm-btn pm-btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;"></i> Buat Periode
        </button>
    </div>

    @if(session('success'))
    <div class="pm-flash pm-flash-success">
        <i data-lucide="check-circle" style="width:15px;height:15px;"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($showForm)
    <div class="pm-card" style="max-width:600px;margin-bottom:1.5rem;">
        <div class="pm-card-header">
            <h3>{{ $editingId ? 'Edit Periode' : 'Buat Periode Baru' }}</h3>
            <button wire:click="closeForm" class="pm-btn pm-btn-ghost pm-btn-icon">
                <i data-lucide="x" style="width:14px;height:14px;"></i>
            </button>
        </div>
        <div class="pm-card-body">
            <form wire:submit="save">
                <div class="pm-form-group">
                    <label class="pm-label">Nama Periode</label>
                    <input type="text" wire:model="name" class="pm-input" placeholder="Contoh: Semester Genap 2026">
                    @error('name') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Tanggal Mulai</label>
                        <input type="date" wire:model="startDate" class="pm-input">
                        @error('startDate') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Tanggal Selesai</label>
                        <input type="date" wire:model="endDate" class="pm-input">
                        @error('endDate') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div style="display:flex;gap:0.6rem;">
                    <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editingId ? 'Update' : 'Simpan' }}</span>
                        <span wire:loading>{{ $editingId ? 'Memperbarui...' : 'Menyimpan...' }}</span>
                    </button>
                    <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

<div class="pm-card" data-anim="fade-up" data-delay="140">
        <div class="pm-table-wrap">
            <table class="pm-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periods as $period)
                    <tr wire:key="period-{{ $period->id }}">
                        <td>{{ $period->name }}</td>
                        <td>{{ $period->start_date->format('d M Y') }} — {{ $period->end_date->format('d M Y') }}</td>
                        <td><span class="pm-badge pm-badge-{{ $period->status_color }}">{{ $period->status_label }}</span></td>
                        <td>
                            <div style="display:flex;gap:0.4rem;">
                                <button wire:click="edit({{ $period->id }})" class="pm-btn pm-btn-ghost pm-btn-sm" title="Edit">
                                    <i data-lucide="edit" style="width:14px;height:14px;"></i>
                                </button>
                                <button wire:click="toggle({{ $period->id }})" class="pm-btn pm-btn-{{ $period->isActive() ? 'danger' : 'success' }} pm-btn-sm" title="{{ $period->isActive() ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i data-lucide="{{ $period->isActive() ? 'lock' : 'unlock' }}" style="width:14px;height:14px;"></i>
                                </button>
                                <button wire:click="delete({{ $period->id }})" wire:confirm="Hapus periode ini? Poin tetap tersimpan." class="pm-btn pm-btn-danger pm-btn-sm" title="Hapus">
                                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="pm-empty">
                                <i data-lucide="gift" style="width:32px;height:32px;"></i>
                                <p>Belum ada periode reward</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($periods->hasPages())
        <div class="pm-table-footer">
            {{ $periods->links() }}
        </div>
        @endif
    </div>
    @if($periods->hasPages())
    <div style="margin-top:1rem;display:flex;justify-content:flex-end;">
        {{ $periods->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function rewardAnim() {
    return {
        init() {}
    };
}
</script>
@endpush
