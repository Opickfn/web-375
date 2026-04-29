<div x-data="fuAnim()" x-init="init()">
    <div class="pm-header" data-anim="slide-down">
        <div>
            <h1 class="pm-h1">Tindak Lanjut</h1>
            <p class="pm-sub">Rencana aksi dan status penyelesaian temuan</p>
        </div>
        <div style="display:flex;gap:0.6rem;">
            <button wire:click="exportFU" class="pm-btn pm-btn-ghost">
                <i data-lucide="download"></i> Export
            </button>

            {{-- HANYA ADMIN & PJ AREA --}}
            @if(!Auth::user()->isPimpinan())
            <button wire:click="openForm" class="pm-btn pm-btn-primary">
                <i data-lucide="plus"></i> Buat Tindak Lanjut
            </button>
            @endif
        </div>
    </div>

    @if($showForm)
    <div class="pm-card" style="margin-bottom:1.25rem;">
        <div class="pm-card-header">
            <h3>Buat Tindak Lanjut Baru</h3>
            <button wire:click="closeForm" class="pm-btn pm-btn-ghost pm-btn-icon"><i data-lucide="x"></i></button>
        </div>
        <div class="pm-card-body">
            <form wire:submit="save">
                <div class="pm-grid-2" style="gap:1rem;">
                    <div class="pm-form-group">
                        <label class="pm-label">Temuan</label>
                        <select wire:model="reportId" class="pm-select-full">
                            <option value="">Pilih temuan...</option>
                            @foreach($approvedReports as $r)
                            <option value="{{ $r->id }}">{{ $r->code }} — {{ Str::limit($r->lokasi, 35) }}</option>
                            @endforeach
                        </select>
                        @error('reportId') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="pm-form-group">
                        <label class="pm-label">Ditugaskan Kepada</label>
                        <input type="text" wire:model="assignedTo" class="pm-input" placeholder="Nama penanggung jawab">
                        @error('assignedTo') <p class="pm-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="pm-form-group">
                    <label class="pm-label">Rencana Tindakan</label>
                    <textarea wire:model="actionPlan" class="pm-textarea" rows="3" placeholder="Deskripsikan rencana..."></textarea>
                    @error('actionPlan') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                <div class="pm-form-group">
                    <label class="pm-label">Target Selesai</label>
                    <input type="date" wire:model="targetDate" class="pm-input" min="{{ now()->format('Y-m-d') }}">
                    @error('targetDate') <p class="pm-form-error">{{ $message }}</p> @enderror
                </div>
                <div style="display:flex;gap:0.6rem;">
                    <button type="submit" class="pm-btn pm-btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                    <button type="button" wire:click="closeForm" class="pm-btn pm-btn-ghost">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="pm-card">
        <div class="pm-table-wrap">
            <table class="pm-table">
                <thead>
                    <tr>
                        <th>Temuan</th>
                        <th>Ditugaskan</th>
                        <th>Rencana</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($followUps as $fu)
                    <tr>
                        <td>{{ $fu->report->code }}</td>
                        <td>{{ $fu->assigned_to_name }}</td>
                        <td>{{ Str::limit($fu->action_plan, 50) }}</td>
                        <td>{{ $fu->target_date->format('d M Y') }}</td>
                        <td>
                            <span class="pm-badge {{ $fu->status == 'completed' ? 'pm-badge-success' : 'pm-badge-warning' }}">
                                {{ ucfirst($fu->status) }}
                            </span>
                        </td>
                        <td>
                            @if($fu->status !== 'completed')
                                {{-- HANYA ADMIN & PJ AREA --}}
                                @if(!Auth::user()->isPimpinan())
                                <button wire:click="complete({{ $fu->id }})" class="pm-btn pm-btn-success pm-btn-sm" onclick="return confirm('Tandai selesai?')">
                                    Selesai
                                </button>
                                @else
                                <span class="pm-badge pm-badge-warning" style="font-size: 0.8rem;">Menunggu Eksekusi</span>
                                @endif
                            @else
                                <span class="pm-badge pm-badge-success" style="font-size: 0.8rem;">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-8">
                            Belum ada tindak lanjut
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $followUps->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function fuAnim() {
    return {
        init() {},
        animRows() {}
    };
}
</script>
@endpush
