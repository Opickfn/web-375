<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - Sistem Pelaporan</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {font-family:'Inter',sans-serif;background:#f8f9fa;margin:0;padding:0;}
    .container {max-width:400px;margin:50px auto;text-align:center;}
    h2 {font-weight:600;margin:10px 0 5px;}
    p.subtitle {color:#555;margin-bottom:30px;font-size:15px;}
    .card {background:white;border:1px solid #ddd;border-radius:10px;padding:20px;box-shadow:0 2px 5px rgba(0,0,0,0.05);text-align:left;}
    label {display:block;margin-bottom:5px;font-size:14px;font-weight:500;}
    input {width:100%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:5px;font-family:'Inter',sans-serif;}
    button {width:100%;background:#007bff;color:white;padding:10px;border:none;border-radius:5px;cursor:pointer;font-weight:500;}
    button:hover {background:#0056b3;}
    p {font-size:14px;text-align:center;}
  </style>
</head>
<body>

<div class="container">
  <img src="<?php echo base_url('assets/images/safety-first.png'); ?>" width="60" alt="Logo">
  <img src="<?php echo base_url('assets/images/polman.png'); ?>" width="50" alt="Logo">
  <h2>Daftar Akun Baru</h2>
  <p class="subtitle">Sistem Pelaporan 5R, 7S & K3</p>

  <div class="card">
    <h3 style="font-size:18px;font-weight:600;">Registrasi Pekerja</h3>
    <p style="margin-bottom:15px;color:#555;font-size:14px;">Buat akun untuk mengakses dashboard dan fitur manajemen</p>

    <form method="POST" action="<?= site_url('auth/proses_register') ?>">
      <label>Nama Lengkap *</label>
      <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>

      <label>Email *</label>
      <input type="email" name="email" placeholder="nama@perusahaan.com" required>

      <label>Password *</label>
      <input type="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>

      <button type="submit">Daftar Akun</button>
    </form>

    <p style="margin-top:15px;">
      Sudah punya akun? <a href="<?= site_url('auth/login') ?>">Login di sini</a>
    </p>
  </div>

  <div class="card" style="text-align:center;">
    <h3 style="margin:0;">Ingin Melaporkan Pelanggaran?</h3>
    <p>Anda dapat membuat laporan tanpa registrasi</p>
    <a href="<?= site_url('pelaporan/form') ?>">
      <button type="button" class="btn-outline">Buat Laporan Publik</button>
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($this->session->flashdata('success')): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= $this->session->flashdata('success'); ?>',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= site_url('auth/login'); ?>";
        }
    });
</script>
<?php endif; ?>

</body>
</html>
