-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Sep 2025 pada 04.31
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pelaporan_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `prioritas` enum('Rendah','Sedang','Tinggi') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Aktif','Selesai','Menunggu Review') DEFAULT 'Aktif',
  `tanggal` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `laporan`
--

INSERT INTO `laporan` (`id`, `kategori`, `lokasi`, `deskripsi`, `prioritas`, `created_at`, `status`, `tanggal`) VALUES
(3, 'K3', 'lab pbl', 'terdapat mahasiswa yang tidak menggunakan sepatu safety ketika praktikum', 'Rendah', '2025-08-27 21:47:38', 'Menunggu Review', '2025-08-28 10:00:19'),
(4, 'K3', 'bengkel', 'tidak menggunakan sepatu safety', 'Tinggi', '2025-08-28 22:55:29', 'Menunggu Review', '2025-08-29 10:55:30'),
(5, 'K3', 'bengkel foundry', 'tidak menggunakan helm ', 'Tinggi', '2025-08-30 06:02:50', 'Menunggu Review', '2025-08-30 18:02:50'),
(6, 'K3', 'bengkel manufaktur', 'tidak menggunakan sepatu safety', 'Tinggi', '2025-09-02 23:40:56', 'Menunggu Review', '2025-09-03 11:40:57'),
(7, 'K3', 'bengkel manufaktur', 'test', 'Rendah', '2025-09-03 04:01:43', 'Menunggu Review', '2025-09-03 16:01:43'),
(8, '5R', 'lantai 2', 'kabel berserakan dilantai', 'Rendah', '2025-09-03 04:06:13', 'Menunggu Review', '2025-09-03 16:06:13'),
(9, 'K3', 'Lantai 1 Teori', 'Kabel tidak rapih', 'Rendah', '2025-09-10 20:23:17', 'Menunggu Review', '2025-09-11 08:23:18'),
(10, 'K3', 'Lantai 3', 'kabel berserakan', 'Rendah', '2025-09-10 20:32:02', 'Menunggu Review', '2025-09-11 08:32:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_selesai`
--

CREATE TABLE `laporan_selesai` (
  `id` int(11) NOT NULL,
  `laporan_id` int(11) NOT NULL,
  `diselesaikan_oleh` varchar(100) NOT NULL,
  `tindakan` varchar(50) NOT NULL,
  `detail` text DEFAULT NULL,
  `pencegahan` text DEFAULT NULL,
  `tanggal_penyelesaian` date NOT NULL,
  `opsi_verifikasi` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelapor`
--

CREATE TABLE `pelapor` (
  `pelapor_id` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `no_hp` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyelesaian`
--

CREATE TABLE `penyelesaian` (
  `penyelesaian_id` int(10) UNSIGNED NOT NULL,
  `laporan_id` int(10) UNSIGNED NOT NULL,
  `deskripsi_penyelesaian` text NOT NULL,
  `tanggal_penyelesaian` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas`
--

CREATE TABLE `petugas` (
  `petugas_id` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`) VALUES
(1, 'arum sita resmi', 'arums@gmail.com', '$2y$10$wa6vcFw.9IeqF1gw73d/ZezLz.QtP7N4kxx2r1ys5tDIst4XeXw72', '2025-08-27 06:46:05'),
(5, 'arum sita resmi', 'arumsitatkj01@gmail.com', '$2y$10$8IS99Xgz0A1vR/UMKRSlj.OWmsNPs4i.u0jQmx1rHKaI0sFHlG13y', '2025-08-27 08:19:41');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_selesai`
--
ALTER TABLE `laporan_selesai`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pelapor`
--
ALTER TABLE `pelapor`
  ADD PRIMARY KEY (`pelapor_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `penyelesaian`
--
ALTER TABLE `penyelesaian`
  ADD PRIMARY KEY (`penyelesaian_id`),
  ADD UNIQUE KEY `uk_penyelesaian_laporan` (`laporan_id`);

--
-- Indeks untuk tabel `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`petugas_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `laporan_selesai`
--
ALTER TABLE `laporan_selesai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelapor`
--
ALTER TABLE `pelapor`
  MODIFY `pelapor_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penyelesaian`
--
ALTER TABLE `penyelesaian`
  MODIFY `penyelesaian_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `petugas`
--
ALTER TABLE `petugas`
  MODIFY `petugas_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
