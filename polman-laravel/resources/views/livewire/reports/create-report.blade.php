<div>
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
                    <label class="form-label">Lokasi Kejadian</label>
                    <input type="text" wire:model="lokasi" class="form-input" placeholder="Contoh: Bengkel Mesin Lt.2">
                    @error('lokasi') <p class="form-error">{{ $message }}</p> @enderror
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
</div>
