<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Pelaporan</title>
  
  <!-- Import font Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f8f9fa;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 400px;
      margin: 50px auto;
      text-align: center;
    }
    h2 {
      font-weight: 600;
      margin: 10px 0 5px;
    }
    p.subtitle {
      color: #555;
      margin-bottom: 30px;
      font-size: 15px;
    }
    .card {
      background: white;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      text-align: left;
    }
    .card h3 {
      margin-bottom: 10px;
      font-weight: 600;
      font-size: 18px;
      display: flex;
      align-items: center;
    }
    .card h3 img {
      margin-right: 8px;
    }
    label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
      font-size: 14px;
      font-weight: 500;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: 'Inter', sans-serif;
    }
    button {
      width: 100%;
      background: #007bff;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-family: 'Inter', sans-serif;
      font-weight: 500;
    }
    button:hover {
      background: #0056b3;
    }
    .btn-outline {
      background: white;
      color: #007bff;
      border: 1px solid #007bff;
      width: auto;
      padding: 10px 20px;
      border-radius: 5px;
      font-family: 'Inter', sans-serif;
      font-weight: 500;
      cursor: pointer;
    }
    .btn-outline:hover {
      background: #007bff;
      color: white;
    }
    p {
      font-size: 14px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Icon di atas judul -->
 <img src="<?php echo base_url('assets/images/safety-first.png'); ?>" width="60" alt="Logo Safety First">
 <img src="<?php echo base_url('assets/images/polman.png'); ?>" width="50" alt="Logo Polman">
  
  <!-- Judul -->
  <h2>Masuk ke Sistem</h2>
  <p class="subtitle">Sistem Pelaporan 5R, 7S & K3</p>

  <!-- Kotak Login -->
  <div class="card">
    <h3>
      <img src="<?php echo base_url('assets/images/login.png'); ?>" width="24" alt="Login Icon">
      Login Pekerja
    </h3>
    <p style="margin-bottom:15px; color:#555; font-size:14px;">Masukkan kredensial Anda untuk mengakses dashboard</p>

    <form method="POST" action="<?= site_url('auth/proses_login') ?>">
      <label>Email</label>
      <input type="email" name="email" placeholder="nama@perusahaan.com" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="********" required>

      <button type="submit">Masuk</button>
    </form>

    <p style="margin-top:15px;">
      Belum punya akun? <a href="<?= site_url('auth/register') ?>">Daftar di sini</a>
    </p>
  </div>

  <!-- Kotak Laporan Publik -->
  <div class="card" style="padding: 20px; text-align: center;">
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
      <h3 style="margin: 0;">Ingin Melaporkan Pelanggaran?</h3>
      <p style="margin: 5px 0 15px 0;">Anda dapat membuat laporan tanpa login</p>
      
      <a href="<?= site_url('pelaporan/form') ?>" style="text-decoration: none;">
        <button type="button" 
                class="btn-outline" 
                style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; border: 1px solid #007bff; border-radius: 6px; background: white; cursor: pointer;">
          <span>Buat Laporan Publik</span>
          <img src="<?= base_url('assets/images/report.png'); ?>" alt="Report Icon" style="width:20px; height:20px;">
        </button>
      </a>
    </div>
  </div>

  


</body>
</html>
