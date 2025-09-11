<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="container mt-4">

    <!-- Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold mb-0">Dashboard</h3>
        <p class="text-muted mb-0">Monitoring sistem pelaporan 5R, 7S, dan K3</p>
    </div>
        <a href="<?php echo site_url('reports'); ?>" class="btn btn-primary">
            Lihat Semua Laporan
        </a>
    </div>
  <!-- Statistik -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card p-3 text-center">
      <h6>Total Laporan</h6>
      <h3 class="fw-bold"><?= $total; ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 text-center">
      <h6>Pelanggaran Aktif</h6>
      <h3 class="fw-bold"><?= $aktif; ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 text-center">
      <h6>Diselesaikan</h6>
      <h3 class="fw-bold"><?= $selesai; ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 text-center">
      <h6>Menunggu Review</h6>
      <h3 class="fw-bold"><?= $review; ?></h3>
    </div>
  </div>
</div>

<!-- Laporan Terbaru -->
<div class="card p-3 mb-4">
  <h5 class="fw-bold mb-1">Laporan Terbaru</h5>
  <hr>
  <?php if(!empty($latest)): ?>
    <?php foreach($latest as $r): ?>
      <div class="mb-2">
        <strong><?= $r->kategori; ?></strong> - <?= $r->lokasi; ?><br>
        <small><?= $r->deskripsi; ?></small>
      </div>
      <hr>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center text-muted my-3">📄 Belum ada laporan</p>
  <?php endif; ?>
</div>


</div>
</body>
</html>
