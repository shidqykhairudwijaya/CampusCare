-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jun 2026 pada 15.10
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `campuscare`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `level_akses` varchar(50) DEFAULT 'Super Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `level_akses`) VALUES
(4, 'Super Admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `id_mahasiswa` int(11) NOT NULL,
  `id_teknisi` int(11) DEFAULT NULL,
  `id_ruangan` int(11) NOT NULL,
  `nama_fasilitas` varchar(100) NOT NULL,
  `kategori` enum('Elektronik','Furniture','Internet','Kebersihan','Lainnya') NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) NOT NULL,
  `status` enum('Menunggu','Menunggu Respon','Diproses','Sedang Dikerjakan','Selesai') DEFAULT 'Menunggu',
  `catatan_teknisi` text DEFAULT NULL,
  `tanggal_lapor` timestamp NOT NULL DEFAULT current_timestamp(),
  `tenggat_waktu` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan`
--

INSERT INTO `laporan` (`id`, `id_mahasiswa`, `id_teknisi`, `id_ruangan`, `nama_fasilitas`, `kategori`, `deskripsi`, `foto`, `status`, `catatan_teknisi`, `tanggal_lapor`, `tenggat_waktu`) VALUES
(36, 38, 39, 28, 'AC', 'Elektronik', 'kurang dinggin', '1779966505_38.png', 'Menunggu Respon', NULL, '2026-05-28 11:08:25', '2026-05-31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `kelas` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `kelas`) VALUES
(38, '4D');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_ruangan`
--

CREATE TABLE `master_ruangan` (
  `id` int(11) NOT NULL,
  `fakultas` varchar(100) NOT NULL,
  `lantai` varchar(50) NOT NULL,
  `ruangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `master_ruangan`
--

INSERT INTO `master_ruangan` (`id`, `fakultas`, `lantai`, `ruangan`) VALUES
(24, 'FISIP', '', ''),
(25, 'FISIP', '4', '4.77'),
(27, 'FASILKOM', '', ''),
(28, 'FASILKOM', '3', 'perpustakaan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teknisi`
--

CREATE TABLE `teknisi` (
  `id_teknisi` int(11) NOT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `keahlian` enum('Elektronik','Furniture','Internet','Kebersihan','Lainnya') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `teknisi`
--

INSERT INTO `teknisi` (`id_teknisi`, `no_wa`, `keahlian`) VALUES
(37, '085691693498', 'Elektronik'),
(39, '085691693498', 'Elektronik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('mahasiswa','admin','teknisi') NOT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `nama`, `role`, `otp_code`, `otp_expire`) VALUES
(4, 'admin@campuscare.com', '$2y$10$ltAXpXo.8NF/W5H0IFev1uXLwyz8Ep1uyl15pQKStWxCWrF4mz7X2', 'Administrator Utama', 'admin', '622008', '2026-05-06 04:30:52'),
(37, 'ikiganteng07@gmail.com', '$2y$10$aKrAwSI/R6IxmM1dzTgp4eiWjww9Xbza8/o0Y09Z1pB8z2AW8gKDe', 'Faerul', 'teknisi', '160869', '2026-05-15 06:27:28'),
(38, '2410631170109@student.unsika.ac.id', '$2y$10$sI.CWwJGWcT5mZmklz9zJ.KW4j6eS0nceA/wRfF39Ce.wYSPqGp4u', 'Shidqy Khairu Dwijaya ', 'mahasiswa', NULL, NULL),
(39, 'shidqykhairudwijayaa@gmail.com', '$2y$10$rNcuOuw9ID/xC36M8ter/eDHIlcXI3rIe.4ORHaSyWSPgxL83Kldm', 'shidqy khairu dwijaya', 'teknisi', '236262', '2026-05-28 13:16:45');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_teknisi` (`id_teknisi`),
  ADD KEY `fk_laporan_ruangan` (`id_ruangan`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`);

--
-- Indeks untuk tabel `master_ruangan`
--
ALTER TABLE `master_ruangan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  ADD PRIMARY KEY (`id_teknisi`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `master_ruangan`
--
ALTER TABLE `master_ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `fk_laporan_ruangan` FOREIGN KEY (`id_ruangan`) REFERENCES `master_ruangan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `laporan_ibfk_2` FOREIGN KEY (`id_teknisi`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  ADD CONSTRAINT `teknisi_ibfk_1` FOREIGN KEY (`id_teknisi`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
