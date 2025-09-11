<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pelaporan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
        }
        .hero {
            padding: 80px 20px;
            text-align: center;
            background: #f9fbfd;
        }
        .hero h1 {
            font-weight: bold;
        }
        .feature-card {
            border-radius: 12px;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .footer {
            background: #eaf3ff;
            padding: 40px 20px;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Sistem Pelaporan <br><span class="text-primary">5R, 7S & K3</span></h1>
        <p class="mt-3">Platform digital untuk melaporkan dan mengelola pelanggaran continuous improvement di tempat kerja dengan mudah dan efisien</p>
        <div class="mt-4">
            <!-- Arahkan ke form_laporan.php -->
            <a href="<?php echo site_url('pelaporan/form'); ?>" class="btn btn-primary btn-lg">Buat Laporan</a>
            <a href="<?= site_url('auth/login') ?>" class="btn btn-outline-secondary btn-lg">Login Pekerja</a>


        </div>
    </section>

    <!-- Fitur Utama -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Fitur Utama</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <h5>📋 Pelaporan Mudah</h5>
                    <p>Laporan pelanggaran 5R, 7S dan K3 dengan form yang sederhana dan intuitif</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <h5>📊 Dashboard Monitoring</h5>
                    <p>Monitor dan analisa status laporan dengan dashboard yang informatif</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <h5>📎 Bukti Digital</h5>
                    <p>Upload foto dan video sebagai bukti pendukung laporan pelanggaran</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kategori Pelaporan -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Kategori Pelaporan</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <h5>5R (5S)</h5>
                    <ul>
                        <li>Ringkas (Seiri)</li>
                        <li>Rapi (Seiton)</li>
                        <li>Resik (Seiso)</li>
                        <li>Rawat (Seiketsu)</li>
                        <li>Rajin (Shitsuke)</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <h5>7S</h5>
                    <ul>
                        <li>Seiri (Sort)</li>
                        <li>Seiton (Set in Order)</li>
                        <li>Seiso (Shine)</li>
                        <li>Seiketsu (Standardize)</li>
                        <li>Shitsuke (Sustain)</li>
                        <li>Safety</li>
                        <li>Security</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <h5>K3</h5>
                    <ul>
                        <li>Keselamatan Kerja</li>
                        <li>Kesehatan Kerja</li>
                        <li>Penggunaan APD</li>
                        <li>Prosedur Safety</li>
                        <li>Manajemen Risiko</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        <h4>Mulai Berkontribusi untuk Keselamatan Kerja</h4>
        <p>Lapor pelanggaran yang Anda temukan dan bantu menciptakan lingkungan kerja yang lebih aman</p>
        <a href="<?php echo site_url('pelaporan/form'); ?>" class="btn btn-primary">
            Buat Laporan Sekarang
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
