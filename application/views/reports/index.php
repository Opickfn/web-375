<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Pelanggaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f9fafb; }
    .card-laporan {
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-bottom: 15px;
    }
    .badge { font-size: 0.75rem; }
    .filter-box {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px;
        background: #fff;
        margin-bottom: 20px;
    }
    .btn-action {
        display: flex;
        gap: 10px;
    }
  </style>
</head>
<body class="container py-4">

  <h3 class="mb-2">Daftar Pelanggaran</h3>
  <p class="text-muted">Monitor dan kelola semua laporan pelanggaran 5R, 7S, dan K3</p>

  <!-- Filter & Search -->
  <div class="filter-box">
    <form method="get" class="row g-2 align-items-center">
      <div class="col-md-4">
        <input type="text" name="q" class="form-control" placeholder="Cari laporan..." value="<?= $this->input->get('q'); ?>">
      </div>
      <div class="col-md-3">
        <select name="kategori" class="form-select" onchange="this.form.submit()">
          <option value="">Semua Kategori</option>
          <option value="5R" <?= ($this->input->get('kategori')=='5R'?'selected':''); ?>>5R</option>
          <option value="7S" <?= ($this->input->get('kategori')=='7S'?'selected':''); ?>>7S</option>
          <option value="K3" <?= ($this->input->get('kategori')=='K3'?'selected':''); ?>>K3</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select" onchange="this.form.submit()">
          <option value="">Semua Status</option>
          <option value="Aktif" <?= ($this->input->get('status')=='Aktif'?'selected':''); ?>>Aktif</option>
          <option value="Selesai" <?= ($this->input->get('status')=='Selesai'?'selected':''); ?>>Selesai</option>
          <option value="Menunggu Review" <?= ($this->input->get('status')=='Menunggu Review'?'selected':''); ?>>Menunggu Review</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
      </div>
    </form>
  </div>

  <!-- List laporan -->
  <?php if (!empty($laporan)) : ?>
    <?php foreach ($laporan as $row): ?>
      <div class="card card-laporan p-3">
        <div class="d-flex justify-content-between">
          <div>
            <h6 class="mb-1">#LP<?= str_pad($row->id, 3, '0', STR_PAD_LEFT); ?></h6>
            <small class="text-muted">
              📅 <?= $row->tanggal; ?> • 📍 <?= $row->lokasi; ?>
            </small>
          </div>
          <div>
            <span class="badge bg-danger"><?= $row->prioritas; ?></span>
            <span class="badge bg-info"><?= $row->kategori; ?></span>
            <span class="badge bg-warning"><?= $row->status; ?></span>
          </div>
        </div>
        <div class="mt-2">
          <strong><?= $row->deskripsi; ?></strong>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
          <span class="text-muted small">📷 <?= rand(1,3); ?> bukti foto/video</span>
          <div class="btn-action">
            <a href="#" data-id="<?= $row->id; ?>" 
            class="btn btn-sm btn-outline-secondary btn-detail">👁️ Detail</a>
            <a href="<?= site_url('reports/export/'.$row->id); ?>" class="btn btn-sm btn-outline-secondary">⬇️ Export</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-warning">Belum ada laporan ditemukan.</div>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal kosong -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="modalDetailContent">
        <!-- Isi detail akan dimuat via AJAX -->
        </div>
    </div>
    </div>

    <script>
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = btn.dataset.id; // ambil ID dari data-id
                const url = '<?= site_url('reports/detail/'); ?>' + id;

                // ambil HTML dari controller detail()
                const html = await (await fetch(url)).text();
                document.getElementById('modalDetailContent').innerHTML = html;

                // tampilkan modal bootstrap
                const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
                modal.show();
        });
     });
    </script>
    <script>
        document.querySelectorAll('.btn-resolve').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = btn.dataset.id;
            const url = '<?= site_url('reports/resolve_form/'); ?>' + id;

            const html = await (await fetch(url)).text();
            document.getElementById('modalDetailContent').innerHTML = html;

            const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
            modal.show();
        });
    });
    </script>


</body>
</html>
