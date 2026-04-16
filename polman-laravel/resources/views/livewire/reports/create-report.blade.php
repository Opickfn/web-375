<div>
    @if($showSuccess && $successReport)
    {{-- Success Confirmation --}}
    <div style="max-width:600px;margin:80px auto;padding:0 24px;">
        <div class="card animate-in">
            <div class="card-body" style="text-align:center;padding:40px 24px;">
                <div style="width:80px;height:80px;background:rgba(16,185,129,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;color:#10b981;">
                    <i data-lucide="check-circle" style="width:40px;height:40px;"></i>
                </div>

                <h2 style="margin:0 0 12px 0;color:var(--text-dark);">Laporan Berhasil Dikirim!</h2>
                <p style="color:var(--text-muted);margin:0 0 32px 0;font-size:0.95rem;">
                    Terima kasih atas kontribusi Anda. Laporan akan ditinjau oleh tim manajemen.
                </p>

                {{-- Points Earned --}}
                <div style="background:rgba(245,197,24,0.1);border:2px solid #fdc034;border-radius:var(--radius);padding:16px;margin-bottom:24px;display:flex;align-items:center;justify-content:center;gap:12px;">
                    <i data-lucide="star" style="width:24px;height:24px;color:#fdc034;"></i>
                    <div style="text-align:center;">
                        <p style="margin:0;font-size:1.3rem;color:var(--text-dark);font-weight:700;">+10 Poin</p>
                        <p style="margin:0;font-size:0.85rem;color:var(--text-muted);">Submit laporan</p>
                    </div>
                </div>

                {{-- Report Details --}}
                <div style="background:var(--bg-surface);border-radius:var(--radius);padding:20px;margin-bottom:32px;border-left:4px solid #10b981;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;text-align:left;">
                        <div>
                            <p style="margin:0 0 4px 0;font-size:0.85rem;color:var(--text-muted);font-weight:500;">KODE LAPORAN</p>
                            <p style="margin:0;font-size:1.1rem;color:var(--text-dark);font-weight:600;">{{ $successReport->code }}</p>
                        </div>
                        <div>
                            <p style="margin:0 0 4px 0;font-size:0.85rem;color:var(--text-muted);font-weight:500;">KATEGORI</p>
                            <p style="margin:0;"><span class="badge badge-info">{{ $successReport->kategori }}</span></p>
                        </div>
                        <div>
                            <p style="margin:0 0 4px 0;font-size:0.85rem;color:var(--text-muted);font-weight:500;">PRIORITAS</p>
                            <p style="margin:0;">
                                <span class="badge {{ $successReport->prioritas === 'tinggi' ? 'badge-danger' : ($successReport->prioritas === 'sedang' ? 'badge-warning' : 'badge-neutral') }}">
                                    {{ ucfirst($successReport->prioritas) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p style="margin:0 0 4px 0;font-size:0.85rem;color:var(--text-muted);font-weight:500;">STATUS</p>
                            <p style="margin:0;"><span class="badge badge-warning">Menunggu Review</span></p>
                        </div>
                        <div style="grid-column:1/-1;">
                            <p style="margin:0 0 4px 0;font-size:0.85rem;color:var(--text-muted);font-weight:500;">LOKASI</p>
                            <p style="margin:0;color:var(--text-dark);">{{ $successReport->location_breadcrumb }}</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3" style="display:flex;gap:12px;justify-content:center;">
                    <button wire:click="resetForm" class="btn btn-primary btn-lg">
                        <i data-lucide="plus" style="width:18px;height:18px;"></i>
                        Buat Laporan Baru
                    </button>
                    <a href="{{ route('reports.my') }}" class="btn btn-outline btn-lg">
                        <i data-lucide="file-text" style="width:18px;height:18px;"></i>
                        Lihat Laporan Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Form --}}
    <div class="page-header">
        <h1>Buat Laporan Baru</h1>
        <p>Laporkan temuan perbaikan 5R, 7S, atau K3</p>
    </div>

    <div class="card animate-in" style="max-width:680px;">
        <div class="card-body">
            <form wire:submit="submit">
                <div class="form-group">
                    <label class="form-label">Kategori Pelanggaran</label>
                    <select wire:model="kategori" class="form-select">
                        <option value="">Pilih kategori</option>
                        <option value="5R">5R (Ringkas, Rapi, Resik, Rawat, Rajin)</option>
                        <option value="7S">7S (Seiri, Seiton, Seiso, Seiketsu, Shitsuke, Safety, Semangat)</option>
                        <option value="K3">K3 (Keselamatan dan Kesehatan Kerja)</option>
                    </select>
                    @error('kategori') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kampus</label>
                    <select wire:model="campus_id" wire:change="$refresh" class="form-select">
                        <option value="">Pilih kampus</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                        @endforeach
                    </select>
                    @error('campus_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Cabang Lokasi</label>
                    <select id="reportBranchId" wire:key="report-branch-{{ $campus_id ?: 'none' }}" wire:model="branch_id" wire:change="$refresh" class="form-select" @disabled(!$campus_id)>
                        <option value="">Pilih Gedung atau Infrastruktur</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" data-type="{{ $branch->type }}">{{ ucfirst($branch->type) }} - {{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <p class="form-error">{{ $message }}</p> @enderror
                    @if($branchType === 'gedung')
                        <p class="text-sm text-muted" style="margin-top:4px;">Pilih gedung terlebih dahulu, lalu lanjutkan ke lantai dan ruang.</p>
                    @elseif($branchType === 'infrastruktur')
                        <p class="text-sm text-muted" style="margin-top:4px;">Infrastruktur umum dipilih langsung sebagai lokasi akhir.</p>
                    @endif
                </div>

                @if($branchType === 'gedung')
                <div id="reportFloorGroup" class="form-group">
                    <label class="form-label">Lantai</label>
                    <select wire:model="floor_id" wire:change="$refresh" class="form-select">
                        <option value="">Pilih lantai</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                        @endforeach
                    </select>
                    @error('floor_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                @if($floor_id)
                <div id="reportSpaceGroup" class="form-group">
                    <label class="form-label">Ruang / Area</label>
                    <select wire:model="space_id" class="form-select">
                        <option value="">Pilih ruangan atau area</option>
                        @foreach($spaces as $space)
                            <option value="{{ $space->id }}">{{ ucfirst($space->type) }} - {{ $space->name }}</option>
                        @endforeach
                    </select>
                    @error('space_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                @endif
                @endif

                <div class="form-group">
                    <label class="form-label">Detail Lokasi</label>
                    <input type="text" wire:model="detail_lokasi" class="form-input" placeholder="Contoh: Dekat pintu masuk area kerja">
                    @error('detail_lokasi') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Temuan</label>
                    <textarea wire:model="deskripsi" class="form-textarea" placeholder="Jelaskan detail temuan yang ditemukan di lapangan..."></textarea>
                    @error('deskripsi') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tingkat Prioritas</label>
                    <select wire:model="prioritas" class="form-select">
                        <option value="rendah">Rendah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                    @error('prioritas') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Bukti Foto (opsional)</label>
                    <input type="file" wire:model="bukti" class="form-input" accept="image/*">
                    @error('bukti') <p class="form-error">{{ $message }}</p> @enderror

                    @if($bukti)
                        <img src="{{ $bukti->temporaryUrl() }}" alt="Preview" style="margin-top:8px; max-height:200px; border-radius:var(--radius);">
                    @endif
                </div>

                <div class="flex gap-3 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                        <i data-lucide="send" style="width:18px;height:18px;"></i>
                        <span wire:loading.remove>Kirim Laporan</span>
                        <span wire:loading>Mengirim...</span>
                    </button>
                    <a href="{{ route('reports.my') }}" class="btn btn-outline btn-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
