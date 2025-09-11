<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Pelanggaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .report-card {
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 16px;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .status-badge {
      font-size: 0.8rem;
      padding: 4px 10px;
      border-radius: 9999px;
    }
    .status-menunggu { background: #fff3cd; color: #856404; }
    .status-proses { background: #cce5ff; color: #004085; }
    .status-selesai { background: #d4edda; color: #155724; }
    .priority-tinggi { color: #dc3545; font-weight: bold; }
    .priority-sedang { color: #ffc107; font-weight: bold; }
    .priority-rendah { color: #28a745; font-weight: bold; }
  </style>
</head>
<body class="bg-light">
<div class="container my-4">

  <h2 class="fw-bold mb-3">Daftar Pelanggaran</h2>
  <p class="text-muted">Monitor dan kelola semua laporan pelanggaran 5R, 7S, dan K3</p>

  <!-- Filter & Pencarian -->
  <div class="card mb-4 p-3">
    <div class="row g-2 align-items-center">
      <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Cari laporan...">
      </div>
      <div class="col-md-3">
        <select class="form-select">
          <option>Semua Kategori</option>
          <option>5R</option>
          <option>7S</option>
          <option>K3</option>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select">
          <option>Semua Status</option>
          <option>Menunggu</option>
          <option>Proses</option>
          <option>Selesai</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Loop data laporan dari database -->
  <?php $no = 1; foreach($laporan as $row): ?>
  <div class="report-card">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold">#LP<?= $no++ ?></span>
        <span class="ms-2 
          <?= strtolower($row->prioritas)=='tinggi' ? 'priority-tinggi' : (strtolower($row->prioritas)=='sedang' ? 'priority-sedang' : 'priority-rendah') ?>">
          <?= ucfirst($row->prioritas) ?>
        </span>
        <span class="status-badge 
          <?= strtolower($row->status)=='menunggu review' ? 'status-menunggu' : (strtolower($row->status)=='proses' ? 'status-proses' : 'status-selesai') ?>">
          <?= $row->status ?>
        </span>
      </div>
      <small class="text-muted"><?= $row->tanggal ?></small>
    </div>
    <h6 class="mb-1"><?= $row->kategori ?> - <?= $row->lokasi ?></h6>
    <p class="mb-2"><?= $row->deskripsi ?></p>
    <div class="d-flex gap-2">
      <!-- Tombol Detail pakai JS -->
      <button type="button" class="btn btn-sm btn-outline-primary btn-detail" data-id="<?= $row->id ?>">Detail</button>
      <a href="<?= site_url('reports/export/'.$row->id) ?>" class="btn btn-sm btn-outline-secondary">Export</a>
    </div>
  </div>
  <?php endforeach; ?>

</div>

<!-- Modal kosong untuk detail/resolve -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content" id="modalDetailContent">
      <!-- Isi modal akan dimuat via AJAX -->
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('click', async (e) => {
  // Klik tombol Detail
  if(e.target.matches('.btn-detail')) {
    e.preventDefault();
    const id = e.target.dataset.id;
    const url = '<?= site_url('reports/detail/'); ?>' + id;
    const html = await (await fetch(url)).text();
    document.getElementById('modalDetailContent').innerHTML = html;
    const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
    modal.show();
  }

  // Klik tombol Selesaikan Laporan (di dalam modal)
  if(e.target.matches('#btnSelesaikan')) {
    const id = e.target.dataset.id;
    const url = '<?= site_url('reports/resolve_form/'); ?>' + id;
    const html = await (await fetch(url)).text();
    document.getElementById('modalDetailContent').innerHTML = html;
  }

    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content" id="modalDetailContent">
        <!-- Isi akan diganti via AJAX -->
      </div>
    </div>
  </div>

  public function resolve_form($id) {
    // ambil data laporan dari model
    $data['lap'] = $this->Reports_model->get_by_id($id);

    // tampilkan view form penyelesaian
    $this->load->view('reports/resolve_view', $data);
}

// Klik tombol "Selesaikan Laporan" -> load form
document.addEventListener('click', async (e) => {
  if(e.target.matches('#btnSelesaikan')) {
    const id = e.target.dataset.id;
    const url = '<?= site_url('reports/resolve_form/'); ?>' + id;
    const html = await (await fetch(url)).text();
    document.getElementById('modalDetailContent').innerHTML = html;
  }
});

// Submit form penyelesaian via AJAX
document.addEventListener('submit', async (e) => {
  if(e.target.id === 'formResolve') {
    e.preventDefault();
    const form = e.target;
    const url = form.action;
    const formData = new FormData(form);

    const res = await fetch(url, {method: 'POST', body: formData});
    const data = await res.json();

    if(data.success) {
      alert(data.message); // bisa diganti sweetalert
      location.reload();   // reload halaman supaya status berubah
    }
  }
});

});
</script>
</body>
</html>
