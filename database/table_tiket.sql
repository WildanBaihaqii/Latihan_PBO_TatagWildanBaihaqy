-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2026 at 08:29 PM
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
-- Database: `db_latihan_pbo_trpl1a_tatagwildanbaihaqy`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_tiket`
--

CREATE TABLE `table_tiket` (
  `id_tiket` varchar(10) NOT NULL,
  `nama_film` varchar(100) NOT NULL,
  `jadwal_tayang` datetime NOT NULL,
  `jumlah_kursi` int NOT NULL,
  `harga_dasar_tiket` decimal(10,2) NOT NULL,
  `jenis_studio` enum('Regular','IMAX','Velvet') NOT NULL,
  `lokasi_baris` varchar(5) DEFAULT NULL,
  `tipe_audio` varchar(30) DEFAULT NULL,
  `kacamata_3d_id` varchar(15) DEFAULT NULL,
  `efek_gerak_fitur` varchar(50) DEFAULT NULL,
  `bantal_selimut_pack` varchar(10) DEFAULT NULL,
  `layanan_butler` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `table_tiket`
--

INSERT INTO `table_tiket` (`id_tiket`, `nama_film`, `jadwal_tayang`, `jumlah_kursi`, `harga_dasar_tiket`, `jenis_studio`, `lokasi_baris`, `tipe_audio`, `kacamata_3d_id`, `efek_gerak_fitur`, `bantal_selimut_pack`, `layanan_butler`) VALUES
('TKT001', 'Avengers: Secret Wars', '2026-07-15 13:00:00', 1, 50000.00, 'Regular', 'G', 'Dolby Atmos 7.1', NULL, NULL, NULL, NULL),
('TKT002', 'Avengers: Secret Wars', '2026-07-15 13:00:00', 2, 50000.00, 'Regular', 'G', 'Dolby Atmos 7.1', NULL, NULL, NULL, NULL),
('TKT003', 'Ngeri-Ngeri Sedap 2', '2026-07-15 14:30:00', 1, 40000.00, 'Regular', 'E', 'Dolby Digital', NULL, NULL, NULL, NULL),
('TKT004', 'Ngeri-Ngeri Sedap 2', '2026-07-15 14:30:00', 1, 40000.00, 'Regular', 'F', 'Dolby Digital', NULL, NULL, NULL, NULL),
('TKT005', 'The Batman Part II', '2026-07-16 19:00:00', 1, 55000.00, 'Regular', 'C', 'Dolby Atmos 7.1', NULL, NULL, NULL, NULL),
('TKT006', 'The Batman Part II', '2026-07-16 19:00:00', 1, 55000.00, 'Regular', 'C', 'Dolby Atmos 7.1', NULL, NULL, NULL, NULL),
('TKT007', 'KKN di Desa Penari 2', '2026-07-17 21:15:00', 1, 45000.00, 'Regular', 'D', 'Dolby Digital', NULL, NULL, NULL, NULL),
('TKT008', 'Interstellar: Re-Release', '2026-07-15 10:00:00', 1, 95000.00, 'IMAX', 'J', 'IMAX 12-Ch', NULL, 'Standard Vibration', NULL, NULL),
('TKT009', 'Interstellar: Re-Release', '2026-07-15 10:00:00', 1, 95000.00, 'IMAX', 'J', 'IMAX 12-Ch', NULL, 'Standard Vibration', NULL, NULL),
('TKT010', 'Avatar 3: Fire and Ash', '2026-07-15 15:00:00', 1, 110000.00, 'IMAX', 'H', 'IMAX 12-Ch', 'GLS-3D-098', 'Heavy Motion Fitur', NULL, NULL),
('TKT011', 'Avatar 3: Fire and Ash', '2026-07-15 15:00:00', 1, 110000.00, 'IMAX', 'H', 'IMAX 12-Ch', 'GLS-3D-099', 'Heavy Motion Fitur', NULL, NULL),
('TKT012', 'Avatar 3: Fire and Ash', '2026-07-15 18:30:00', 1, 110000.00, 'IMAX', 'K', 'IMAX 12-Ch', 'GLS-3D-104', 'Heavy Motion Fitur', NULL, NULL),
('TKT013', 'Dune: Part Three', '2026-07-16 13:00:00', 1, 100000.00, 'IMAX', 'L', 'IMAX Sonics', NULL, 'Subwoofer Bass', NULL, NULL),
('TKT014', 'Dune: Part Three', '2026-07-16 13:00:00', 1, 100000.00, 'IMAX', 'L', 'IMAX Sonics', NULL, 'Subwoofer Bass', NULL, NULL),
('TKT015', 'The Batman Part II', '2026-07-15 20:00:00', 2, 250000.00, 'Velvet', 'A', 'Dolby Atmos', NULL, NULL, 'PKT-PRM-01', 'F&B Service On-Call'),
('TKT016', 'The Batman Part II', '2026-07-15 20:00:00', 2, 250000.00, 'Velvet', 'A', 'Dolby Atmos', NULL, NULL, 'PKT-PRM-02', 'F&B Service On-Call'),
('TKT017', 'Avengers: Secret Wars', '2026-07-16 16:00:00', 2, 300000.00, 'Velvet', 'B', 'Dolby Atmos', NULL, NULL, 'PKT-VIP-11', 'Full Butler Assistance'),
('TKT018', 'Avengers: Secret Wars', '2026-07-16 16:00:00', 2, 300000.00, 'Velvet', 'B', 'Dolby Atmos', NULL, NULL, 'PKT-VIP-12', 'Full Butler Assistance'),
('TKT019', 'Ngeri-Ngeri Sedap 2', '2026-07-17 14:00:00', 2, 220000.00, 'Velvet', 'C', 'Dolby Digital', NULL, NULL, 'PKT-REG-05', 'Welcome Drink Only'),
('TKT020', 'Ngeri-Ngeri Sedap 2', '2026-07-17 14:00:00', 2, 220000.00, 'Velvet', 'C', 'Dolby Digital', NULL, NULL, 'PKT-REG-06', 'Welcome Drink Only');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_tiket`
--
ALTER TABLE `table_tiket`
  ADD PRIMARY KEY (`id_tiket`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
