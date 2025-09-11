<!DOCTYPE html>
<html lang="id">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<head>
  <meta charset="UTF-8">
  <title>Buat Laporan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container my-5">
    <h2 class="mb-4">Form Laporan</h2>
    <form action="<?php echo site_url('pelaporan/simpan'); ?>" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
      
      <div class="mb-3">
        <label class="form-label">Kategori Pelanggaran *</label>
        <select class="form-select" name="kategori" required>
          <option value="">Pilih kategori pelanggaran</option>
          <option>5R</option>
          <option>7S</option>
          <option>K3</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Lokasi Kejadian *</label>
        <input type="text" class="form-control" name="lokasi" placeholder="Contoh: Lantai 2, Ruang Produksi A" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi Pelanggaran *</label>
        <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Tingkat Prioritas</label>
        <select class="form-select" name="prioritas">
          <option>Rendah</option>
          <option>Sedang</option>
          <option>Tinggi</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Bukti Foto/Video</label>
        <input type="file" class="form-control" name="bukti" accept=".jpg,.png,.mp4">
        <div class="form-text">Max 10MB</div>
      </div>

      <button type="submit" class="btn btn-primary">Kirim Laporan</button>
    </form>
  </div>
</body>

    <?php if(!empty($success)): ?>
      <script>
          Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: '<?php echo $success; ?>',
              showConfirmButton: false,
              timer: 2000
          });
      </script>
    <?php endif; ?>



</html>
