-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Des 2024 pada 09.00
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sallary`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `nominal` decimal(15,2) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pemasukan`
--

INSERT INTO `pemasukan` (`id`, `tanggal`, `kategori`, `jenis`, `lokasi`, `nominal`, `qty`, `total`, `created_by`) VALUES
(14, '2024-12-16', 'Kompensasi', 'Kompensasi Kontrak', 'Matahari', 2036000.00, 1, 2036000.00, 'babang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `tanggal`, `kategori`, `jenis`, `lokasi`, `nominal`, `qty`, `total`, `created_by`) VALUES
(8, '2024-12-16', 'Makanan', 'Roti O', 'Mall', 25000.00, 2, 50000.00, 'babang'),
(9, '2024-12-16', 'Makanan', 'Belanja Kedai', 'Dock Yard', 108000.00, 1, 108000.00, 'babang'),
(10, '2024-12-18', 'Makanan', 'Bakso', 'Jaya Mukti', 35000.00, 1, 35000.00, 'babang'),
(11, '2024-12-18', 'Kendaraan', 'Bensin', 'Putri Tujuh', 50000.00, 1, 50000.00, 'babang'),
(12, '2024-12-18', 'Makanan', 'Roti & Selai', 'Indomaret Sudirman', 39000.00, 1, 39000.00, 'babang'),
(13, '2024-12-19', 'Rumah', 'Token Listrik', 'Dumai', 100000.00, 1, 100000.00, 'babang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `name`, `avatar`, `password`, `created_at`, `role`) VALUES
(3, 'dedek', 'dedek@gmail.com', 'Suci', 'qr-code (4).png', '$2y$10$eFonpiEW5L1fAwYGjTbUquYvfoCEKQhBhkWJ9IHsCbVOqgyCsYpWG', '2024-12-19 04:53:54', 'admin'),
(6, 'samba', 'samba@gmail.com', 'Divisi 1', 'qr-code (5).png', '$2y$10$RL0iuct2.N6Japl0EgUwdOlGrMXPRW.g1KSdpUIzdRiMfozwOJbmy', '2024-12-19 05:17:58', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
