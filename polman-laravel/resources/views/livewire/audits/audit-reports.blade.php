<div>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1>Audit PJ Area</h1>
            <p>Input laporan audit untuk penanggung jawab area</p>
        </div>
        <button wire:click="openForm" class="btn btn-primary">
            <i data-lucide="file-plus" style="width:16px;height:16px;"></i> Buat Audit Baru
        </button>
    </div>

    @if($showForm)
    <div class="card mb-4 animate-in">
        <div class="card-header"><h3>{{ $editId ? 'Edit Laporan Audit' : 'Input Laporan Audit' }}</h3></div>
        <div class="card-body">
            <form wire:submit="save" enctype="multipart/form-data">
                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Target Audit</label>
                        <select wire:model="auditTarget" wire:change="$refresh" class="form-select">
                            <option value="pj_area">PJ Area</option>
                            <option value="lokasi">Lokasi</option>
                        </select>
                        @error('auditTarget') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Audit</label>
                        <input type="date" wire:model="auditDate" class="form-input">
                        @error('auditDate') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($auditTarget === 'pj_area')
                <div class="form-group">
                    <label class="form-label">PJ Area</label>
                    <select wire:model="pjAreaId" wire:change="$refresh" class="form-select">
                        <option value="">Pilih PJ Area</option>
                        @foreach($pjAreas as $pjArea)
                            <option value="{{ $pjArea->id }}">{{ $pjArea->full_name }}</option>
                        @endforeach
                    </select>
                    @error('pjAreaId') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">
                        {{ $auditTarget === 'pj_area' ? 'Lokasi Tanggung Jawab PJ Area' : 'Lokasi yang Diaudit' }}
                    </label>
                    <select id="auditBranchId" wire:key="audit-branch-{{ $auditTarget }}-{{ $pjAreaId ?: 'none' }}" wire:model="branchId" wire:change="$refresh" class="form-select" @disabled($auditTarget === 'pj_area' ? !$pjAreaId : false)>
                        <option value="">Pilih Lokasi</option>
                        @foreach($this->assignedLocations as $location)
                            <option value="{{ $location->id }}" data-type="{{ $location->type ?? '' }}">{{ $location->label }}</option>
                        @endforeach
                    </select>
                    @error('branchId') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                @if(in_array($this->branchType, ['gedung', 'lantai'], true))
                <div class="grid grid-2 gap-4">
                    @if($this->branchType === 'gedung')
                    <div id="auditFloorGroup" class="form-group">
                        <label class="form-label">Lantai</label>
                        <select wire:model="floorId" wire:change="$refresh" class="form-select">
                            <option value="">Pilih Lantai</option>
                            @foreach($this->floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->label }}</option>
                            @endforeach
                        </select>
                        @error('floorId') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    @endif
                    <div id="auditSpaceGroup" class="form-group">
                        <label class="form-label">Ruangan / Area</label>
                        <select wire:model="spaceId" wire:change="$refresh" class="form-select" @disabled($this->branchType === 'gedung' ? !$this->floorId : !$this->branchId)>
                            <option value="">Pilih Ruangan atau Area</option>
                            @foreach($this->spaces as $space)
                                <option value="{{ $space->id }}">{{ $space->label }}</option>
                            @endforeach
                        </select>
                        @error('spaceId') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Judul Audit</label>
                    <input type="text" wire:model="title" class="form-input" placeholder="Judul laporan audit">
                    @error('title') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Temuan</label>
                    <textarea wire:model="findings" class="form-textarea" placeholder="Deskripsi temuan audit"></textarea>
                    @error('findings') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Rekomendasi</label>
                    <textarea wire:model="recommendations" class="form-textarea" placeholder="Rekomendasi perbaikan (opsional)"></textarea>
                    @error('recommendations') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Upload PDF Laporan Audit</label>
                    <input type="file" wire:model="pdfFile" accept="application/pdf" class="form-control">
                    @error('pdfFile') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">{{ $editId ? 'Perbarui' : 'Simpan' }}</button>
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
                            <th>Judul</th>
                            <th>PJ Area</th>
                            <th>Lokasi Audit</th>
                            <th>Auditor</th>
                            <th>Tanggal Audit</th>
                            <th>File PDF</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditReports as $audit)
                        <tr>
                            <td class="font-medium">{{ $audit->title }}</td>
                            <td>{{ $audit->pjArea?->full_name ?? 'N/A' }}</td>
                            <td>{{ $audit->location?->full_path ?? '-' }}</td>
                            <td>{{ $audit->auditor?->full_name ?? '-' }}</td>
                            <td>{{ $audit->audit_date->format('d M Y') }}</td>
                            <td>
                                @if($audit->pdf_path)
                                    <a href="{{ route('audits.download', $audit) }}" class="btn btn-outline btn-sm">Unduh PDF</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge {{ $audit->status === 'closed' ? 'badge-success' : 'badge-info' }}">{{ ucfirst($audit->status) }}</span></td>
                            <td class="text-right">
                                <button type="button" wire:click="openEdit({{ $audit->id }})" class="btn btn-secondary btn-sm mr-2">Edit</button>
                                <button type="button" wire:click="deleteReport({{ $audit->id }})" onclick="return confirm('Hapus laporan audit ini?');" class="btn btn-danger btn-sm">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i data-lucide="file-text" style="width:40px;height:40px;"></i>
                                    <p>Belum ada laporan audit.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($auditReports->hasPages())
        <div class="card-footer">{{ $auditReports->links() }}</div>
        @endif
    </div>

</div>
