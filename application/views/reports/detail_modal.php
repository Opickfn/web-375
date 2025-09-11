<div class="modal-header">
  <h5 class="modal-title">Detail Laporan #LP<?= str_pad($lap->id,3,'0',STR_PAD_LEFT); ?></h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
  <p><strong>Judul:</strong> <?= isset($lap->judul) ? $lap->judul : '—'; ?></p>
  <p><strong>Deskripsi:</strong> <?= $lap->deskripsi; ?></p>
  <p><strong>Lokasi:</strong> <?= $lap->lokasi; ?></p>
  <p><strong>Tanggal:</strong> <?= $lap->tanggal; ?></p>
  <p><strong>Status:</strong> <?= $lap->status; ?></p>
  <p><strong>Prioritas:</strong> <?= $lap->prioritas; ?></p>
</div>

<div class="modal-footer">
  <button type="button" 
          class="btn btn-primary" 
          id="btnSelesaikan" 
          data-id="<?= $lap->id; ?>">
      Selesaikan Laporan
  </button>
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tandai Sedang Proses</button>
</div>
