-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 01:27 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_trpl1b_kristiana_setyaningsih`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_karyawan`
--

CREATE TABLE `tabel_karyawan` (
  `id_karyawan` int NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `departemen` varchar(50) NOT NULL,
  `hari_kerja_masuk` date NOT NULL,
  `gaji_dasar_per_hari` decimal(10,2) NOT NULL,
  `jenis_karyawan` enum('kontrak','tetap','magang') NOT NULL,
  `durasi_kontrak_bulan` int DEFAULT NULL,
  `agensi_penyalur` varchar(100) DEFAULT NULL,
  `tunjangan_kesehatan` decimal(10,2) DEFAULT NULL,
  `opsi_saham_id` varchar(50) DEFAULT NULL,
  `uang_saku_bulanan` decimal(10,2) DEFAULT NULL,
  `sertifikat_kampus_merdeka` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_karyawan`
--

INSERT INTO `tabel_karyawan` (`id_karyawan`, `nama_karyawan`, `departemen`, `hari_kerja_masuk`, `gaji_dasar_per_hari`, `jenis_karyawan`, `durasi_kontrak_bulan`, `agensi_penyalur`, `tunjangan_kesehatan`, `opsi_saham_id`, `uang_saku_bulanan`, `sertifikat_kampus_merdeka`) VALUES
(1, 'Budi Santoso', 'IT', '2024-01-15', 150000.00, 'kontrak', 12, 'PT Tech Solutions', 500000.00, NULL, NULL, NULL),
(2, 'Siti Rahayu', 'HRD', '2024-02-01', 130000.00, 'kontrak', 6, 'PT Sumber Daya', 400000.00, NULL, NULL, NULL),
(3, 'Agus Wijaya', 'Marketing', '2024-03-10', 140000.00, 'kontrak', 12, 'PT Digital Media', 450000.00, NULL, NULL, NULL),
(4, 'Dewi Kusuma', 'Finance', '2024-01-20', 160000.00, 'kontrak', 8, 'PT Financial Pro', 550000.00, NULL, NULL, NULL),
(5, 'Rudi Hermawan', 'IT', '2024-02-15', 155000.00, 'kontrak', 10, 'PT Tech Solutions', 500000.00, NULL, NULL, NULL),
(6, 'Nina Permata', 'HRD', '2024-03-01', 135000.00, 'kontrak', 6, 'PT Sumber Daya', 400000.00, NULL, NULL, NULL),
(7, 'Hendra Gunawan', 'Marketing', '2024-01-25', 145000.00, 'kontrak', 12, 'PT Digital Media', 450000.00, NULL, NULL, NULL),
(8, 'Dr. Andi Pratama', 'IT', '2020-06-01', 200000.00, 'tetap', NULL, NULL, 1000000.00, 'SAHAM001', NULL, NULL),
(9, 'Ir. Maya Sari', 'Finance', '2019-08-15', 220000.00, 'tetap', NULL, NULL, 1200000.00, 'SAHAM002', NULL, NULL),
(10, 'Rina Wahyuni', 'HRD', '2021-03-10', 180000.00, 'tetap', NULL, NULL, 800000.00, 'SAHAM003', NULL, NULL),
(11, 'Dedi Firmansyah', 'Marketing', '2020-11-01', 190000.00, 'tetap', NULL, NULL, 900000.00, 'SAHAM004', NULL, NULL),
(12, 'Prof. Sri Mulyani', 'IT', '2018-05-20', 250000.00, 'tetap', NULL, NULL, 1500000.00, 'SAHAM005', NULL, NULL),
(13, 'Dr. Budi Utomo', 'Finance', '2019-09-10', 230000.00, 'tetap', NULL, NULL, 1300000.00, 'SAHAM006', NULL, NULL),
(14, 'Ir. Lestari Dewi', 'HRD', '2020-07-05', 195000.00, 'tetap', NULL, NULL, 850000.00, 'SAHAM007', NULL, NULL),
(15, 'Ahmad Fauzi', 'IT', '2024-07-01', 80000.00, 'magang', NULL, NULL, NULL, NULL, 2000000.00, 'Sertifikat MSIB Batch 5'),
(16, 'Putri Indah', 'Marketing', '2024-07-15', 75000.00, 'magang', NULL, NULL, NULL, NULL, 1800000.00, 'Sertifikat Kampus Merdeka'),
(17, 'Rizky Ramadhan', 'Finance', '2024-08-01', 85000.00, 'magang', NULL, NULL, NULL, NULL, 2200000.00, 'Sertifikat MSIB Batch 6'),
(18, 'Sarah Amelia', 'HRD', '2024-07-10', 70000.00, 'magang', NULL, NULL, NULL, NULL, 1700000.00, 'Sertifikat Kampus Merdeka'),
(19, 'Fajar Nugroho', 'IT', '2024-08-15', 90000.00, 'magang', NULL, NULL, NULL, NULL, 2300000.00, 'Sertifikat MSIB Batch 5'),
(20, 'Mega Puspita', 'Marketing', '2024-07-20', 78000.00, 'magang', NULL, NULL, NULL, NULL, 1900000.00, 'Sertifikat Kampus Merdeka');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  MODIFY `id_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
