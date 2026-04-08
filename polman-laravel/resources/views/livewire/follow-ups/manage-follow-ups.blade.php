<div>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1>Tindak Lanjut</h1>
            <p>Rencana dan status penyelesaian masalah</p>
        </div>
        <button wire:click="openForm" class="btn btn-primary">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Buat Tindak Lanjut
        </button>
    </div>

    @if($showForm)
    <div class="card mb-4 animate-in">
        <div class="card-header"><h3>Buat Tindak Lanjut Baru</h3></div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Laporan</label>
                        <select wire:model="reportId" class="form-select">
                            <option value="">Pilih laporan</option>
                            @foreach($approvedReports as $r)
                                <option value="{{ $r->id }}">{{ $r->code }} - {{ \Illuminate\Support\Str::limit($r->lokasi, 30) }}</option>
                            @endforeach
                        </select>
                        @error('reportId') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ditugaskan Kepada</label>
                        <input type="text" wire:model="assignedTo" class="form-input" placeholder="Nama penanggung jawab">
                        @error('assignedTo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Rencana Tindakan</label>
                    <textarea wire:model="actionPlan" class="form-textarea" placeholder="Deskripsikan rencana tindakan..."></textarea>
                    @error('actionPlan') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group" style="max-width:240px;">
                    <label class="form-label">Target Selesai</label>
                    <input type="date" wire:model="targetDate" class="form-input">
                    @error('targetDate') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" wire:click="closeForm" class="btn btn-outline">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Laporan</th>
                            <th>Ditugaskan</th>
                            <th>Rencana</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($followUps as $fu)
                        <tr>
                            <td class="font-medium">{{ $fu->report->code }}</td>
                            <td>{{ $fu->assigned_to_name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($fu->action_plan, 40) }}</td>
                            <td class="text-sm">{{ $fu->target_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $fu->status === 'completed' ? 'badge-success' : ($fu->status === 'in_progress' ? 'badge-info' : 'badge-warning') }}">
                                    {{ $fu->status_label }}
                                </span>
                            </td>
                            <td class="text-right">
                                @if($fu->status !== 'completed')
                                <button wire:click="complete({{ $fu->id }})" class="btn btn-success btn-sm" wire:confirm="Tandai selesai?">
                                    <i data-lucide="check" style="width:14px;height:14px;"></i> Selesai
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i data-lucide="list-checks" style="width:40px;height:40px;"></i>
                                    <p>Belum ada tindak lanjut.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($followUps->hasPages())
        <div class="card-footer">{{ $followUps->links() }}</div>
        @endif
    </div>
</div>
