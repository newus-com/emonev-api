-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table emonev.akun_rekening
CREATE TABLE IF NOT EXISTS `akun_rekening` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) NOT NULL,
  `uraianAkun` text NOT NULL,
  `deskripsiAkun` text NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.akun_rekening: ~3 rows (approximately)
INSERT INTO `akun_rekening` (`id`, `kode`, `uraianAkun`, `deskripsiAkun`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, '5', 'BELANJA', '-', '2023-08-24 15:08:39', '2023-09-15 12:05:24', NULL),
	(2, '14', 'asdasdsadasdadasd', 'asdasdsadsdadasd', '2023-09-10 14:33:27', '2023-09-10 14:33:33', '2023-09-10 14:33:37'),
	(3, '2', 'akun', 'akun', '2023-09-10 16:07:46', NULL, NULL);

-- Dumping structure for table emonev.bidang
CREATE TABLE IF NOT EXISTS `bidang` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `urusanId` bigint NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nomenklatur` longtext NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `urusanId` (`urusanId`),
  CONSTRAINT `bidang_ibfk_1` FOREIGN KEY (`urusanId`) REFERENCES `urusan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.bidang: ~5 rows (approximately)
INSERT INTO `bidang` (`id`, `urusanId`, `kode`, `nomenklatur`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, ' 7.01', 'KECAMATAN', '2023-08-23 20:10:52', '2023-09-17 21:16:47', NULL),
	(2, 1, ' 7.01', 'KECAMATAN', '2023-09-10 09:24:09', '2023-09-17 21:16:27', '2023-09-17 21:16:54'),
	(3, 2, '1', 'bidang update', '2023-09-10 17:25:07', '2023-09-10 17:25:15', NULL),
	(4, 2, '2', 'asdsad', '2023-09-10 17:25:23', '2023-09-10 17:27:16', NULL),
	(5, 2, '3', 'hahaha', '2023-09-10 17:25:59', '2023-09-10 17:26:19', '2023-09-10 17:26:23');

-- Dumping structure for table emonev.detail_anggaran_sub_dpa
CREATE TABLE IF NOT EXISTS `detail_anggaran_sub_dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `subDpaId` bigint NOT NULL,
  `pagu` enum('1','2','3','4') NOT NULL,
  `jumlah` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subDpaId` (`subDpaId`),
  CONSTRAINT `detail_anggaran_sub_dpa_ibfk_1` FOREIGN KEY (`subDpaId`) REFERENCES `sub_dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.detail_anggaran_sub_dpa: ~28 rows (approximately)
INSERT INTO `detail_anggaran_sub_dpa` (`id`, `subDpaId`, `pagu`, `jumlah`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(5, 2, '1', 635850000, '2023-09-14 15:38:41', '2023-09-17 15:23:20', NULL),
	(6, 2, '2', 0, '2023-09-14 15:38:41', '2023-09-17 15:23:20', NULL),
	(7, 2, '3', 0, '2023-09-14 15:38:41', '2023-09-17 15:23:20', NULL),
	(8, 2, '4', 0, '2023-09-14 15:38:41', '2023-09-17 15:23:20', NULL),
	(9, 3, '1', 20000000, '2023-09-14 15:53:34', NULL, NULL),
	(10, 3, '2', 60000000, '2023-09-14 15:53:34', NULL, NULL),
	(11, 3, '3', 80000000, '2023-09-14 15:53:34', NULL, NULL),
	(12, 3, '4', 2000000000, '2023-09-14 15:53:34', NULL, NULL),
	(13, 4, '1', 20000000, '2023-09-14 15:54:58', NULL, NULL),
	(14, 4, '2', 90000000, '2023-09-14 15:54:58', NULL, NULL),
	(15, 4, '3', 199000000, '2023-09-14 15:54:58', NULL, NULL),
	(16, 4, '4', 25000000000, '2023-09-14 15:54:58', NULL, NULL),
	(17, 5, '1', 20000000, '2023-09-14 18:55:01', NULL, NULL),
	(18, 5, '2', 90000000, '2023-09-14 18:55:01', NULL, NULL),
	(19, 5, '3', 80000000, '2023-09-14 18:55:01', NULL, NULL),
	(20, 5, '4', 8000000, '2023-09-14 18:55:01', NULL, NULL),
	(21, 6, '1', 20000000, '2023-09-14 19:04:51', NULL, NULL),
	(22, 6, '2', 60000000, '2023-09-14 19:04:51', NULL, NULL),
	(23, 6, '3', 80000000, '2023-09-14 19:04:51', NULL, NULL),
	(24, 6, '4', 8000000, '2023-09-14 19:04:51', NULL, NULL),
	(25, 7, '1', 20000000, '2023-09-14 19:05:20', NULL, NULL),
	(26, 7, '2', 60000000, '2023-09-14 19:05:20', NULL, NULL),
	(27, 7, '3', 80000000, '2023-09-14 19:05:20', NULL, NULL),
	(28, 7, '4', 8000000, '2023-09-14 19:05:20', NULL, NULL),
	(29, 8, '1', 20000000, '2023-09-14 19:06:16', NULL, NULL),
	(30, 8, '2', 60000000, '2023-09-14 19:06:16', NULL, NULL),
	(31, 8, '3', 80000000, '2023-09-14 19:06:16', NULL, NULL),
	(32, 8, '4', 200000000000, '2023-09-14 19:06:16', NULL, NULL),
	(33, 9, '1', 20000000, '2023-09-16 20:10:50', NULL, NULL),
	(34, 9, '2', 60000000, '2023-09-16 20:10:50', NULL, NULL),
	(35, 9, '3', 80000000, '2023-09-16 20:10:50', NULL, NULL),
	(36, 9, '4', 200000000, '2023-09-16 20:10:50', NULL, NULL),
	(37, 10, '1', 60000, '2023-09-17 15:18:57', NULL, NULL),
	(38, 10, '2', 900000, '2023-09-17 15:18:57', NULL, NULL),
	(39, 10, '3', 190000, '2023-09-17 15:18:57', NULL, NULL),
	(40, 10, '4', 471000000000, '2023-09-17 15:18:57', NULL, NULL),
	(41, 11, '1', 67890, '2023-09-19 07:09:54', '2023-09-19 07:10:42', NULL),
	(42, 11, '2', 87654, '2023-09-19 07:09:54', '2023-09-19 07:10:42', NULL),
	(43, 11, '3', 5464545, '2023-09-19 07:09:54', '2023-09-19 07:10:42', NULL),
	(44, 11, '4', 544564, '2023-09-19 07:09:54', '2023-09-19 07:10:42', NULL);

-- Dumping structure for table emonev.dinas
CREATE TABLE IF NOT EXISTS `dinas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `noHp` varchar(16) NOT NULL,
  `address` text NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.dinas: ~2 rows (approximately)
INSERT INTO `dinas` (`id`, `name`, `email`, `noHp`, `address`, `logo`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(3, 'Dinas Pariwisata', 'pariwisata@mail.com', '0821345728', 'Jl. in aja dulu', 'ini link foto', '2023-08-25 08:13:31', NULL, NULL),
	(4, 'Dinas Pariwisata New', 'pariwisata@mail.com', '0821345728', 'Jl. in aja dulu', 'ini link foto', '2023-08-28 09:09:03', NULL, NULL);

-- Dumping structure for table emonev.dpa
CREATE TABLE IF NOT EXISTS `dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `dinasId` bigint NOT NULL,
  `noDpa` varchar(255) NOT NULL,
  `tahunId` bigint NOT NULL,
  `kegiatanId` bigint DEFAULT NULL,
  `unitId` bigint DEFAULT NULL,
  `jumlahAlokasi` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `penggunaAnggaran` longtext,
  `ttdDpa` longtext,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.dpa: ~10 rows (approximately)
INSERT INTO `dpa` (`id`, `dinasId`, `noDpa`, `tahunId`, `kegiatanId`, `unitId`, `jumlahAlokasi`, `penggunaAnggaran`, `ttdDpa`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 4, 'DPA-0101-00-YI', 1, NULL, NULL, NULL, NULL, NULL, '2023-08-08 12:12:12', NULL, NULL),
	(2, 3, 'TEST-001', 1, 1, 3, '200000000', NULL, NULL, '2023-09-13 09:07:23', '2023-09-15 13:48:35', NULL),
	(3, 3, 'TEST-002', 1, NULL, NULL, '2000000000', NULL, NULL, '2023-09-13 08:39:40', NULL, NULL),
	(4, 4, 'TEST-008', 1, NULL, NULL, '23456756789', NULL, NULL, '2023-09-13 13:19:02', NULL, NULL),
	(5, 4, 'TEST-006', 1, 1, 3, '4000000000', NULL, NULL, '2023-09-13 13:19:55', '2023-09-13 13:20:13', NULL),
	(6, 4, 'sdfghjkl', 1, 1, 3, '23467890', NULL, NULL, '2023-09-13 13:23:19', '2023-09-13 13:24:38', NULL),
	(7, 4, 'TEST-010', 1, 1, 3, '2343434343', NULL, NULL, '2023-09-13 13:25:17', '2023-09-16 09:27:54', NULL),
	(8, 3, 'asdfghjkl', 1, 1, 3, '234567899876', NULL, NULL, '2023-09-14 12:29:55', '2023-09-18 04:32:22', NULL),
	(9, 4, 'TEST-001123', 1, 1, 3, '500000000000', '[{"id":1694983797745,"nama":"Diki Rahmad Sandi","nip":"123456789","jabatan":"Coo"}]', '{"kota":"Bandar Lampung","tanggal":"2023-09-19","data":[{"id":1694983832839,"nama":"SDFD","nip":"34567899","jabatan":"szdfghjk"}]}', '2023-09-14 13:08:52', '2023-09-18 09:57:44', NULL),
	(10, 3, 'TEST-001123-A2', 1, 1, 3, '3000000000', '[{"id":1695109709064,"nama":"asds","nip":"asd","jabatan":"asdf"}]', '{"kota":"asassa","tanggal":"2023-09-19","data":[{"id":1695109800755,"nama":"asasas","nip":"asasas","jabatan":"saassasa"}]}', '2023-09-19 06:39:43', '2023-09-19 07:19:12', NULL);

-- Dumping structure for table emonev.jenis_rekening
CREATE TABLE IF NOT EXISTS `jenis_rekening` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `kelompokRekeningId` bigint NOT NULL,
  `kode` varchar(255) NOT NULL,
  `uraianAkun` text NOT NULL,
  `deskripsiAkun` text NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelompokRekeningId` (`kelompokRekeningId`),
  CONSTRAINT `jenis_rekening_ibfk_1` FOREIGN KEY (`kelompokRekeningId`) REFERENCES `kelompok_rekening` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.jenis_rekening: ~5 rows (approximately)
INSERT INTO `jenis_rekening` (`id`, `kelompokRekeningId`, `kode`, `uraianAkun`, `deskripsiAkun`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, '5.1.02', 'Belanja Barang dan Jasa', '-', '2023-08-24 15:16:40', '2023-09-15 12:06:18', NULL),
	(2, 1, '1', 'test ubah', 'test ubah', '2023-09-10 15:18:32', '2023-09-10 15:27:21', '2023-09-10 15:28:06'),
	(3, 1, '22', '222', '2222', '2023-09-10 15:27:32', NULL, '2023-09-10 15:27:37'),
	(4, 1, '12', 'jenis', 'jenis', '2023-09-10 16:06:57', NULL, NULL),
	(5, 5, '3', 'jenis', 'jenis', '2023-09-10 16:15:06', NULL, NULL),
	(6, 5, '4', 'ini jenis', 'ini jenis', '2023-09-10 16:15:24', NULL, NULL);

-- Dumping structure for table emonev.kegiatan
CREATE TABLE IF NOT EXISTS `kegiatan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `programId` bigint NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nomenklatur` longtext NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `programId` (`programId`),
  CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`programId`) REFERENCES `program` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.kegiatan: ~3 rows (approximately)
INSERT INTO `kegiatan` (`id`, `programId`, `kode`, `nomenklatur`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, '7.01.01.2.01', 'Perencanaan, Penganggaran, dan Evaluasi Kinerja Perangkat Daerah', '2023-08-24 02:06:24', '2023-09-17 21:17:45', NULL),
	(2, 3, '1', 'kegiatan ubah', '2023-09-10 17:52:49', '2023-09-10 17:52:54', '2023-09-10 17:52:57'),
	(3, 3, '1', 'kegiatan', '2023-09-10 17:53:05', NULL, NULL);

-- Dumping structure for table emonev.kelompok_rekening
CREATE TABLE IF NOT EXISTS `kelompok_rekening` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `akunRekeningId` bigint NOT NULL,
  `kode` varchar(255) NOT NULL,
  `uraianAkun` text NOT NULL,
  `deskripsiAkun` text NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `akunRekeningId` (`akunRekeningId`),
  CONSTRAINT `kelompok_rekening_ibfk_1` FOREIGN KEY (`akunRekeningId`) REFERENCES `akun_rekening` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.kelompok_rekening: ~5 rows (approximately)
INSERT INTO `kelompok_rekening` (`id`, `akunRekeningId`, `kode`, `uraianAkun`, `deskripsiAkun`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, '5.1', 'BELANJA OPERASI', '-', '2023-08-24 15:14:19', '2023-09-15 12:05:45', NULL),
	(2, 1, '1', 'test ubah', 'test ubah', '2023-09-10 15:26:39', '2023-09-10 15:27:00', '2023-09-10 15:27:03'),
	(3, 3, '3', 'kelompok', 'kelompok', '2023-09-10 16:08:05', NULL, NULL),
	(4, 3, '5', 'kelompok', 'kelompok', '2023-09-10 16:08:27', NULL, NULL),
	(5, 3, '6', 'kelompok', 'asd', '2023-09-10 16:14:49', NULL, NULL);

-- Dumping structure for table emonev.ket_sub_dpa
CREATE TABLE IF NOT EXISTS `ket_sub_dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `subDpaId` bigint NOT NULL,
  `subRincianObjekRekeningId` bigint NOT NULL,
  `satuanId` bigint NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `spesifikasi` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `koefisien` int NOT NULL,
  `harga` varchar(255) NOT NULL DEFAULT '0',
  `ppn` bigint NOT NULL,
  `jumlah` varchar(255) NOT NULL DEFAULT '0',
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subDpaId` (`subDpaId`),
  CONSTRAINT `ket_sub_dpa_ibfk_1` FOREIGN KEY (`subDpaId`) REFERENCES `sub_dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.ket_sub_dpa: ~5 rows (approximately)
INSERT INTO `ket_sub_dpa` (`id`, `subDpaId`, `subRincianObjekRekeningId`, `satuanId`, `uraian`, `spesifikasi`, `koefisien`, `harga`, `ppn`, `jumlah`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 2, 1, 1, 'Baliho/banner (termasuk biaya pasang)', '-', 1, '280000', 0, '280000', '2023-08-08 12:12:12', '2023-09-17 15:15:51', NULL),
	(2, 2, 2, 1, 'makan rapat biasa Jawa Tengah', '-', 1, '29020000', 0, '29020000', '2023-08-08 12:12:12', '2023-09-17 15:16:22', NULL),
	(3, 2, 1, 1, 'Test', '-', 10, '970000', 0, '9700000', '2023-09-17 09:04:18', '2023-09-17 15:15:11', NULL),
	(4, 2, 1, 1, 'Test 2', '-', 1, '300000000', 10, '270000000', '2023-09-17 09:06:59', '2023-09-17 15:09:41', '2023-09-17 17:03:04'),
	(5, 11, 1, 1, 'Test 23', '-', 1, '200000', 0, '200000', '2023-09-19 07:25:15', '2023-09-19 07:29:22', NULL);

-- Dumping structure for table emonev.module
CREATE TABLE IF NOT EXISTS `module` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.module: ~18 rows (approximately)
INSERT INTO `module` (`id`, `name`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 'Menu', '1692726391209', NULL, NULL),
	(2, 'Dinas', '1692726391209', NULL, NULL),
	(3, 'Role', '1692726391209', NULL, NULL),
	(4, 'User', '1692726391209', NULL, NULL),
	(5, 'Tahun', '1692726391209', NULL, NULL),
	(6, 'Wilayah', '1692726391209', NULL, NULL),
	(7, 'Satuan', '1692726391209', NULL, NULL),
	(8, 'SumberDana', '1692726391209', NULL, NULL),
	(9, 'Perencanaan', '1692726391209', NULL, NULL),
	(10, 'Rekening', '1692726391209', NULL, NULL),
	(11, 'Organisasi', '1692726391209', NULL, NULL),
	(12, 'KomponenPembangunan', '1692726391209', NULL, NULL),
	(13, 'DPA Anggaran', '1692726391209', NULL, NULL),
	(14, 'RencanaPengambilan', '1692726391209', NULL, NULL),
	(15, 'LaporanAnggaran', '1692726391209', NULL, NULL);

-- Dumping structure for table emonev.objek_rekening
CREATE TABLE IF NOT EXISTS `objek_rekening` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `jenisRekeningId` bigint NOT NULL,
  `kode` varchar(255) NOT NULL,
  `uraianAkun` text NOT NULL,
  `deskripsiAkun` text NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jenisRekeningId` (`jenisRekeningId`),
  CONSTRAINT `objek_rekening_ibfk_1` FOREIGN KEY (`jenisRekeningId`) REFERENCES `jenis_rekening` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.objek_rekening: ~3 rows (approximately)
INSERT INTO `objek_rekening` (`id`, `jenisRekeningId`, `kode`, `uraianAkun`, `deskripsiAkun`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, '5.1.02.01', 'Belanja Barang', '-', '2023-08-24 15:20:24', '2023-09-15 12:06:45', NULL),
	(2, 1, '1', 'test ubah', 'test tambah', '2023-09-10 15:46:04', '2023-09-10 15:46:18', '2023-09-10 15:46:22'),
	(3, 6, '2', 'objek', 'objek', '2023-09-10 16:20:46', NULL, NULL),
	(4, 6, '5', 'ini objek', 'ini objek', '2023-09-10 16:21:06', NULL, NULL);

-- Dumping structure for table emonev.organisasi
CREATE TABLE IF NOT EXISTS `organisasi` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `bidangId` bigint NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nomenklatur` longtext NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bidangId` (`bidangId`),
  CONSTRAINT `organisasi_ibfk_1` FOREIGN KEY (`bidangId`) REFERENCES `bidang` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.organisasi: ~0 rows (approximately)
INSERT INTO `organisasi` (`id`, `bidangId`, `kode`, `nomenklatur`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 2, '1', 'asd', '2023-08-24 16:05:57', '2023-09-10 09:39:10', '2023-09-10 14:01:02'),
	(2, 1, '2.1', 'asdasdadsa', '2023-09-07 15:34:57', NULL, NULL);

-- Dumping structure for table emonev.permission
CREATE TABLE IF NOT EXISTS `permission` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `moduleId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `moduleId` (`moduleId`),
  CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`moduleId`) REFERENCES `module` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.permission: ~158 rows (approximately)
INSERT INTO `permission` (`id`, `name`, `description`, `moduleId`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 'M_TAHUN', 'Menu Tahun', 1, '1692726391209', NULL, NULL),
	(2, 'M_WILAYAH', 'Menu Wilayah', 1, '1692726391209', NULL, NULL),
	(3, 'M_SATUAN', 'Menu Satuan', 1, '1692726391209', NULL, NULL),
	(4, 'M_SUMBER_DANA', 'Menu Sumber Dana', 1, '1692726391209', NULL, NULL),
	(5, 'M_PERENCANAAN', 'Menu Perencanaan', 1, '1692726391209', NULL, NULL),
	(6, 'M_REKENING', 'Menu Rekening', 1, '1692726391209', NULL, NULL),
	(7, 'M_ORGANISASI', 'Menu Organisasi', 1, '1692726391209', NULL, NULL),
	(8, 'M_KOMPONEN_PEMBANGUNAN', 'Menu Komponen Pembangunan', 1, '1692726391209', NULL, NULL),
	(9, 'M_DPA_ANGGARAN', 'Menu DPA Anggaran', 1, '1692726391209', NULL, NULL),
	(10, 'M_RENCANA_PENGAMBILAN', 'Menu Rencana Pengambilan', 1, '1692726391209', NULL, NULL),
	(11, 'M_LAPORAN_ANGGARAN', 'Menu Laporan Anggaran', 1, '1692726391209', NULL, NULL),
	(12, 'M_DPA_PEMBANGUNAN', 'Menu DPA Pembangunan', 1, '1692726391209', NULL, NULL),
	(13, 'M_MONITORING_PEMBANGUNAN', 'Menu Monitoring Pembangunan', 1, '1692726391209', NULL, NULL),
	(14, 'M_LAPORAN_PEMBANGUNAN', 'Menu Laporan Pembangunan', 1, '1692726391209', NULL, NULL),
	(15, 'M_ROLE', 'Menu Role', 1, '1692726391209', NULL, NULL),
	(16, 'M_USER', 'Menu User', 1, '1692726391209', NULL, NULL),
	(17, 'M_DINAS', 'Menu Dinas', 1, '1692726391209', NULL, NULL),
	(18, 'M_DASHBOARD', 'Menu Dashborad', 1, '1692726391209', NULL, NULL),
	(19, 'C_DINAS', 'Tambah Dinas', 2, '1692726391209', NULL, NULL),
	(20, 'U_DINAS', 'Ubah Dinas', 2, '1692726391209', NULL, NULL),
	(21, 'D_DINAS', 'Hapus Dinas', 2, '1692726391209', NULL, NULL),
	(22, 'R_DINAS', 'Lihat Dinas', 2, '1692726391209', NULL, NULL),
	(23, 'C_DINAS_USER', 'Tambah Dinas Ke User', 2, '1692726391209', NULL, NULL),
	(24, 'D_DINAS_USER', 'Hapus Dinas User', 2, '1692726391209', NULL, NULL),
	(25, 'R_DINAS_USER', 'Lihat Dinas User', 2, '1692726391209', NULL, NULL),
	(26, 'C_ROLE', 'Tambah Role', 3, '1692726391209', NULL, NULL),
	(27, 'U_ROLE', 'Ubah Role', 3, '1692726391209', NULL, NULL),
	(28, 'D_ROLE', 'Hapus Role', 3, '1692726391209', NULL, NULL),
	(29, 'R_ROLE', 'Lihat Role', 3, '1692726391209', NULL, NULL),
	(30, 'C_ROLE_PERMISSION', 'Tambah Role Permission', 3, '1692726391209', NULL, NULL),
	(31, 'D_ROLE_PERMISSION', 'Hapus Role Permission', 3, '1692726391209', NULL, NULL),
	(32, 'R_ROLE_PERMISSION', 'Lihat Role Permission', 3, '1692726391209', NULL, NULL),
	(33, 'C_USER', 'Tambah User', 4, '1692726391209', NULL, NULL),
	(34, 'U_USER', 'Ubah User', 4, '1692726391209', NULL, NULL),
	(35, 'D_USER', 'Hapus User', 4, '1692726391209', NULL, NULL),
	(36, 'R_USER', 'Lihat User', 4, '1692726391209', NULL, NULL),
	(37, 'C_USER_ROLE', 'Tambah User Role', 4, '1692726391209', NULL, NULL),
	(38, 'D_USER_ROLE', 'Hapus User Role', 4, '1692726391209', NULL, NULL),
	(39, 'R_USER_ROLE', 'Lihat User Role', 4, '1692726391209', NULL, NULL),
	(40, 'C_USER_SPECIAL_PERMISSION', 'Tambah User Special Permission', 4, '1692726391209', NULL, NULL),
	(41, 'D_USER_SPECIAL_PERMISSION', 'Hapus User Special Permission', 4, '1692726391209', NULL, NULL),
	(42, 'R_USER_SPECIAL_PERMISSION', 'Lihat User Special Permission', 4, '1692726391209', NULL, NULL),
	(43, 'C_TAHUN', 'Tambah Tahun', 5, '1692726391209', NULL, NULL),
	(44, 'U_TAHUN', 'Ubah Tahun', 5, '1692726391209', NULL, NULL),
	(45, 'D_TAHUN', 'Hapus Tahun', 5, '1692726391209', NULL, NULL),
	(46, 'R_TAHUN', 'Lihat Tahun', 5, '1692726391209', NULL, NULL),
	(47, 'C_WILAYAH', 'Tambah Wilayah', 6, '1692726391209', NULL, NULL),
	(48, 'U_WILAYAH', 'Ubah Wilayah', 6, '1692726391209', NULL, NULL),
	(49, 'D_WILAYAH', 'Hapus Wilayah', 6, '1692726391209', NULL, NULL),
	(50, 'R_WILAYAH', 'Lihat Wilayah', 6, '1692726391209', NULL, NULL),
	(51, 'C_SATUAN', 'Tambah Satuan', 7, '1692726391209', NULL, NULL),
	(52, 'U_SATUAN', 'Ubah Satuan', 7, '1692726391209', NULL, NULL),
	(53, 'D_SATUAN', 'Hapus Satuan', 7, '1692726391209', NULL, NULL),
	(54, 'R_SATUAN', 'Lihat Satuan', 7, '1692726391209', NULL, NULL),
	(55, 'C_SUMBER_DANA', 'Tambah Sumber Dana', 8, '1692726391209', NULL, NULL),
	(56, 'U_SUMBER_DANA', 'Ubah Sumber Dana', 8, '1692726391209', NULL, NULL),
	(57, 'D_SUMBER_DANA', 'Hapus Sumber Dana', 8, '1692726391209', NULL, NULL),
	(58, 'R_SUMBER_DANA', 'Lihat Sumber Dana', 8, '1692726391209', NULL, NULL),
	(59, 'C_PERENCANAAN', 'Tambah Perencanaan', 9, '1692726391209', NULL, NULL),
	(60, 'U_PERENCANAAN', 'Ubah Perencanaan', 9, '1692726391209', NULL, NULL),
	(61, 'D_PERENCANAAN', 'Hapus Perencanaan', 9, '1692726391209', NULL, NULL),
	(62, 'R_PERENCANAAN', 'Lihat Perencanaan', 9, '1692726391209', NULL, NULL),
	(63, 'C_URUSAN', 'Tambah Urusan', 9, '1692726391209', NULL, NULL),
	(64, 'U_URUSAN', 'Ubah Urusan', 9, '1692726391209', NULL, NULL),
	(65, 'D_URUSAN', 'Hapus Urusan', 9, '1692726391209', NULL, NULL),
	(66, 'R_URUSAN', 'Lihat Urusan', 9, '1692726391209', NULL, NULL),
	(67, 'C_BIDANG', 'Tambah Bidang', 9, '1692726391209', NULL, NULL),
	(68, 'U_BIDANG', 'Ubah Bidang', 9, '1692726391209', NULL, NULL),
	(69, 'D_BIDANG', 'Hapus Bidang', 9, '1692726391209', NULL, NULL),
	(70, 'R_BIDANG', 'Lihat Bidang', 9, '1692726391209', NULL, NULL),
	(71, 'C_PROGRAM', 'Tambah Program', 9, '1692726391209', NULL, NULL),
	(72, 'U_PROGRAM', 'Ubah Program', 9, '1692726391209', NULL, NULL),
	(73, 'D_PROGRAM', 'Hapus Program', 9, '1692726391209', NULL, NULL),
	(74, 'R_PROGRAM', 'Lihat Program', 9, '1692726391209', NULL, NULL),
	(75, 'C_KEGIATAN', 'Tambah Kegiatan', 9, '1692726391209', NULL, NULL),
	(76, 'U_KEGIATAN', 'Ubah Kegiatan', 9, '1692726391209', NULL, NULL),
	(77, 'D_KEGIATAN', 'Hapus Kegiatan', 9, '1692726391209', NULL, NULL),
	(78, 'R_KEGIATAN', 'Lihat Kegiatan', 9, '1692726391209', NULL, NULL),
	(79, 'C_SUB_KEGIATAN', 'Tambah Sub Kegiatan', 9, '1692726391209', NULL, NULL),
	(80, 'U_SUB_KEGIATAN', 'Ubah Sub Kegiatan', 9, '1692726391209', NULL, NULL),
	(81, 'D_SUB_KEGIATAN', 'Hapus Sub Kegiatan', 9, '1692726391209', NULL, NULL),
	(82, 'R_SUB_KEGIATAN', 'Lihat Sub Kegiatan', 9, '1692726391209', NULL, NULL),
	(83, 'C_REKENING', 'Tambah Rekening', 10, '1692726391209', NULL, NULL),
	(84, 'U_REKENING', 'Ubah Rekening', 10, '1692726391209', NULL, NULL),
	(85, 'D_REKENING', 'Hapus Rekening', 10, '1692726391209', NULL, NULL),
	(86, 'R_REKENING', 'Lihat Rekening', 10, '1692726391209', NULL, NULL),
	(87, 'C_AKUN_REKENING', 'Tambah Akun Rekening', 10, '1692726391209', NULL, NULL),
	(88, 'U_AKUN_REKENING', 'Ubah Akun Rekening', 10, '1692726391209', NULL, NULL),
	(89, 'D_AKUN_REKENING', 'Hapus Akun Rekening', 10, '1692726391209', NULL, NULL),
	(90, 'R_AKUN_REKENING', 'Lihat Akun Rekening', 10, '1692726391209', NULL, NULL),
	(91, 'C_KELOMPOK_REKENING', 'Tambah Kelompok Rekening', 10, '1692726391209', NULL, NULL),
	(92, 'U_KELOMPOK_REKENING', 'Ubah Kelompok Rekening', 10, '1692726391209', NULL, NULL),
	(93, 'D_KELOMPOK_REKENING', 'Hapus Kelompok Rekening', 10, '1692726391209', NULL, NULL),
	(94, 'R_KELOMPOK_REKENING', 'Lihat Kelompok Rekening', 10, '1692726391209', NULL, NULL),
	(95, 'C_JENIS_REKENING', 'Tambah Jenis Rekening', 10, '1692726391209', NULL, NULL),
	(96, 'U_JENIS_REKENING', 'Ubah Jenis Rekening', 10, '1692726391209', NULL, NULL),
	(97, 'D_JENIS_REKENING', 'Hapus Jenis Rekening', 10, '1692726391209', NULL, NULL),
	(98, 'R_JENIS_REKENING', 'Lihat Jenis Rekening', 10, '1692726391209', NULL, NULL),
	(99, 'C_OBJEK_REKENING', 'Tambah Objek Rekening', 10, '1692726391209', NULL, NULL),
	(100, 'U_OBJEK_REKENING', 'Ubah Objek Rekening', 10, '1692726391209', NULL, NULL),
	(101, 'D_OBJEK_REKENING', 'Hapus Objek Rekening', 10, '1692726391209', NULL, NULL),
	(102, 'R_OBJEK_REKENING', 'Lihat Objek Rekening', 10, '1692726391209', NULL, NULL),
	(103, 'C_RINCIAN_OBJEK_REKENING', 'Tambah Rincian Objek Rekening', 10, '1692726391209', NULL, NULL),
	(104, 'U_RINCIAN_OBJEK_REKENING', 'Ubah Rincian Objek Rekening', 10, '1692726391209', NULL, NULL),
	(105, 'D_RINCIAN_OBJEK_REKENING', 'Hapus Rincian Objek Rekening', 10, '1692726391209', NULL, NULL),
	(106, 'R_RINCIAN_OBJEK_REKENING', 'Lihat Rincian Objek Rekening', 10, '1692726391209', NULL, NULL),
	(107, 'R_SUB_RINCIAN_OBJEK_REKENING', 'Lihat Sub Rincian Objek Rekening', 10, '1692726391209', NULL, NULL),
	(108, 'C_SUB_RINCIAN_OBJEK_REKENING', 'Tambah Sub Rincian Objek Rekening', 10, '1692726391209', NULL, NULL),
	(109, 'U_SUB_RINCIAN_OBJEK_REKENING', 'Ubah Sub Rincian Objek Rekening', 10, '1692726391209', NULL, NULL),
	(110, 'D_SUB_RINCIAN_OBJEK_REKENING', 'Hapus Sub Rincian Objek Rekening', 10, '1692726391209', NULL, NULL),
	(111, 'C_ORGANISASI', 'Tambah Organisasi', 11, '1692726391209', NULL, NULL),
	(112, 'U_ORGANISASI', 'Ubah Organisasi', 11, '1692726391209', NULL, NULL),
	(113, 'D_ORGANISASI', 'Hapus Organisasi', 11, '1692726391209', NULL, NULL),
	(114, 'R_ORGANISASI', 'Lihat Organisasi', 11, '1692726391209', NULL, NULL),
	(115, 'C_URUSAN_ORGANISASI', 'Tambah Urusan Organisasi', 11, '1692726391209', NULL, NULL),
	(116, 'U_URUSAN_ORGANISASI', 'Ubah Urusan Organisasi', 11, '1692726391209', NULL, NULL),
	(117, 'D_URUSAN_ORGANISASI', 'Hapus Urusan Organisasi', 11, '1692726391209', NULL, NULL),
	(118, 'R_URUSAN_ORGANISASI', 'Lihat Urusan Organisasi', 11, '1692726391209', NULL, NULL),
	(119, 'C_BIDANG_ORGANISASI', 'Tambah Bidang Organisasi', 11, '1692726391209', NULL, NULL),
	(120, 'U_BIDANG_ORGANISASI', 'Ubah Bidang Organisasi', 11, '1692726391209', NULL, NULL),
	(121, 'D_BIDANG_ORGANISASI', 'Hapus Bidang Organisasi', 11, '1692726391209', NULL, NULL),
	(122, 'R_BIDANG_ORGANISASI', 'Lihat Bidang Organisasi', 11, '1692726391209', NULL, NULL),
	(123, 'C_UNIT', 'Tambah Unit', 11, '1692726391209', NULL, NULL),
	(124, 'U_UNIT', 'Ubah Unit', 11, '1692726391209', NULL, NULL),
	(125, 'D_UNIT', 'Hapus Unit', 11, '1692726391209', NULL, NULL),
	(126, 'R_UNIT', 'Lihat Unit', 11, '1692726391209', NULL, NULL),
	(127, 'C_KOMPONEN_PEMBANGUNAN', 'Tambah Komponen Pembangunan', 12, '1692726391209', NULL, NULL),
	(128, 'U_KOMPONEN_PEMBANGUNAN', 'Ubah Komponen Pembangunan', 12, '1692726391209', NULL, NULL),
	(129, 'D_KOMPONEN_PEMBANGUNAN', 'Hapus Komponen Pembangunan', 12, '1692726391209', NULL, NULL),
	(130, 'R_KOMPONEN_PEMBANGUNAN', 'Lihat Komponen Pembangunan', 12, '1692726391209', NULL, NULL),
	(131, 'C_DPA', 'Tambah DPA', 13, '1692726391209', NULL, NULL),
	(132, 'U_DPA', 'Ubah DPA', 13, '1692726391209', NULL, NULL),
	(133, 'D_DPA', 'Hapus DPA', 13, '1692726391209', NULL, NULL),
	(134, 'R_DPA', 'Lihat DPA', 13, '1692726391209', NULL, NULL),
	(135, 'U_INFORMASI_DPA', 'Ubah Informasi DPA', 13, '1692726391209', NULL, NULL),
	(136, 'R_INFORMASI_DPA', 'Lihat Informasi DPA', 13, '1692726391209', NULL, NULL),
	(137, 'C_SUB_KEGIATAN_DPA', 'Tambah Sub Kegiatan DPA', 13, '1692726391209', NULL, NULL),
	(138, 'U_SUB_KEGIATAN_DPA', 'Ubah Sub Kegiatan DPA', 13, '1692726391209', NULL, NULL),
	(139, 'D_SUB_KEGIATAN_DPA', 'Hapus Sub Kegiatan DPA', 13, '1692726391209', NULL, NULL),
	(140, 'R_SUB_KEGIATAN_DPA', 'Lihat Sub Kegiatan DPA', 13, '1692726391209', NULL, NULL),
	(141, 'C_RINCIAN_BELANJA_DPA', 'Tambah Rincian Belanja DPA', 13, '1692726391209', NULL, NULL),
	(142, 'U_RINCIAN_BELANJA_DPA', 'Ubah Rincian Belanja DPA', 13, '1692726391209', NULL, NULL),
	(143, 'D_RINCIAN_BELANJA_DPA', 'Hapus Rincian Belanja DPA', 13, '1692726391209', NULL, NULL),
	(144, 'R_RINCIAN_BELANJA_DPA', 'Lihat Rincian Belanja DPA', 13, '1692726391209', NULL, NULL),
	(145, 'U_RENCANA_PENARIKAN_DPA', 'Ubah Rencana Penarikan DPA', 13, '1692726391209', NULL, NULL),
	(146, 'R_RENCANA_PENARIKAN_DPA', 'Lihat Rencana Penarikan DPA', 13, '1692726391209', NULL, NULL),
	(147, 'C_PENGGUNA_ANGGARAN_DPA', 'Tambah Pengguna Anggaran DPA', 13, '1692726391209', NULL, NULL),
	(148, 'U_PENGGUNA_ANGGARAN_DPA', 'Ubah Pengguna Anggaran DPA', 13, '1692726391209', NULL, NULL),
	(149, 'D_PENGGUNA_ANGGARAN_DPA', 'Hapus Pengguna Anggaran DPA', 13, '1692726391209', NULL, NULL),
	(150, 'R_PENGGUNA_ANGGARAN_DPA', 'Lihat Pengguna Anggaran DPA', 13, '1692726391209', NULL, NULL),
	(151, 'C_TANDA_TANGAN_DPA', 'Tambah Tanda Tangan DPA', 13, '1692726391209', NULL, NULL),
	(152, 'U_TANDA_TANGAN_DPA', 'Ubah Tanda Tangan DPA', 13, '1692726391209', NULL, NULL),
	(153, 'D_TANDA_TANGAN_DPA', 'Hapus Tanda Tangan DPA', 13, '1692726391209', NULL, NULL),
	(154, 'R_TANDA_TANGAN_DPA', 'Lihat Tanda Tangan DPA', 13, '1692726391209', NULL, NULL),
	(155, 'U_REALIASI_ANGGARAN', 'Ubah Realisasi Anggaran', 14, '1692726391209', NULL, NULL),
	(156, 'R_RENCANA_PENGAMBILAN', 'Lihat Rencana Pengambilan', 14, '1692726391209', NULL, NULL),
	(157, 'R_REALIASI_ANGGARAN', 'Lihat Realisasi Anggaran', 14, '1692726391209', NULL, NULL),
	(158, 'R_LAPORAN_ANGGARAN', 'Lihat Laporan Anggaran', 15, '1692726391209', NULL, NULL);

-- Dumping structure for table emonev.program
CREATE TABLE IF NOT EXISTS `program` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `bidangId` bigint NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nomenklatur` longtext NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bidangId` (`bidangId`),
  CONSTRAINT `program_ibfk_1` FOREIGN KEY (`bidangId`) REFERENCES `bidang` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.program: ~3 rows (approximately)
INSERT INTO `program` (`id`, `bidangId`, `kode`, `nomenklatur`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, ' 7.01.01', 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH KABUPATEN/KOTA', '2023-08-24 02:02:46', '2023-09-17 21:17:16', NULL),
	(2, 3, '1', 'program test ubah', '2023-09-10 17:41:11', '2023-09-10 17:41:19', '2023-09-10 17:41:25'),
	(3, 3, '4', 'program', '2023-09-10 17:41:33', NULL, NULL);

-- Dumping structure for table emonev.rencana_penarikan_dpa
CREATE TABLE IF NOT EXISTS `rencana_penarikan_dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `dpaId` bigint NOT NULL,
  `pagu` enum('1','2','3','4') NOT NULL,
  `bulan` varchar(255) NOT NULL,
  `jumlah` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dpaId` (`dpaId`),
  CONSTRAINT `rencana_penarikan_dpa_ibfk_1` FOREIGN KEY (`dpaId`) REFERENCES `dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.rencana_penarikan_dpa: ~96 rows (approximately)
INSERT INTO `rencana_penarikan_dpa` (`id`, `dpaId`, `pagu`, `bulan`, `jumlah`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 9, '1', 'Januari', 100000000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(2, 9, '2', 'Januari', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(3, 9, '3', 'Januari', 700000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(4, 9, '4', 'Januari', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(5, 9, '1', 'Februari', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(6, 9, '2', 'Februari', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(7, 9, '3', 'Februari', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(8, 9, '4', 'Februari', 800000000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(9, 9, '1', 'Maret', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(10, 9, '2', 'Maret', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(11, 9, '3', 'Maret', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(12, 9, '4', 'Maret', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(13, 9, '1', 'April', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(14, 9, '2', 'April', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(15, 9, '3', 'April', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(16, 9, '4', 'April', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(17, 9, '1', 'Mei', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(18, 9, '2', 'Mei', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(19, 9, '3', 'Mei', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(20, 9, '4', 'Mei', 34000000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(21, 9, '1', 'Juni', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(22, 9, '2', 'Juni', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(23, 9, '3', 'Juni', 9000000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(24, 9, '4', 'Juni', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(25, 9, '1', 'Juli', 3000000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(26, 9, '2', 'Juli', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(27, 9, '3', 'Juli', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(28, 9, '4', 'Juli', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(29, 9, '1', 'Agustus', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(30, 9, '2', 'Agustus', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(31, 9, '3', 'Agustus', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(32, 9, '4', 'Agustus', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(33, 9, '1', 'September', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(34, 9, '2', 'September', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(35, 9, '3', 'September', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(36, 9, '4', 'September', 220000000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(37, 9, '1', 'Oktober', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(38, 9, '2', 'Oktober', 22000000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(39, 9, '3', 'Oktober', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(40, 9, '4', 'Oktober', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(41, 9, '1', 'November', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(42, 9, '2', 'November', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(43, 9, '3', 'November', 10000000, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(44, 9, '4', 'November', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(45, 9, '1', 'Desember', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(46, 9, '2', 'Desember', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(47, 9, '3', 'Desember', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(48, 9, '4', 'Desember', 0, '2023-09-16 19:49:07', '2023-09-18 07:32:01', NULL),
	(49, 10, '1', 'Januari', 0, '2023-09-19 07:46:40', NULL, NULL),
	(50, 10, '2', 'Januari', 3000, '2023-09-19 07:46:40', NULL, NULL),
	(51, 10, '3', 'Januari', 0, '2023-09-19 07:46:40', NULL, NULL),
	(52, 10, '4', 'Januari', 0, '2023-09-19 07:46:40', NULL, NULL),
	(53, 10, '1', 'Februari', 0, '2023-09-19 07:46:40', NULL, NULL),
	(54, 10, '2', 'Februari', 0, '2023-09-19 07:46:40', NULL, NULL),
	(55, 10, '3', 'Februari', 0, '2023-09-19 07:46:40', NULL, NULL),
	(56, 10, '4', 'Februari', 0, '2023-09-19 07:46:40', NULL, NULL),
	(57, 10, '1', 'Maret', 0, '2023-09-19 07:46:40', NULL, NULL),
	(58, 10, '2', 'Maret', 0, '2023-09-19 07:46:40', NULL, NULL),
	(59, 10, '3', 'Maret', 0, '2023-09-19 07:46:40', NULL, NULL),
	(60, 10, '4', 'Maret', 0, '2023-09-19 07:46:40', NULL, NULL),
	(61, 10, '1', 'April', 0, '2023-09-19 07:46:40', NULL, NULL),
	(62, 10, '2', 'April', 0, '2023-09-19 07:46:40', NULL, NULL),
	(63, 10, '3', 'April', 0, '2023-09-19 07:46:40', NULL, NULL),
	(64, 10, '4', 'April', 0, '2023-09-19 07:46:40', NULL, NULL),
	(65, 10, '1', 'Mei', 0, '2023-09-19 07:46:40', NULL, NULL),
	(66, 10, '2', 'Mei', 0, '2023-09-19 07:46:40', NULL, NULL),
	(67, 10, '3', 'Mei', 0, '2023-09-19 07:46:40', NULL, NULL),
	(68, 10, '4', 'Mei', 0, '2023-09-19 07:46:40', NULL, NULL),
	(69, 10, '1', 'Juni', 0, '2023-09-19 07:46:40', NULL, NULL),
	(70, 10, '2', 'Juni', 0, '2023-09-19 07:46:40', NULL, NULL),
	(71, 10, '3', 'Juni', 0, '2023-09-19 07:46:40', NULL, NULL),
	(72, 10, '4', 'Juni', 0, '2023-09-19 07:46:40', NULL, NULL),
	(73, 10, '1', 'Juli', 0, '2023-09-19 07:46:40', NULL, NULL),
	(74, 10, '2', 'Juli', 0, '2023-09-19 07:46:40', NULL, NULL),
	(75, 10, '3', 'Juli', 0, '2023-09-19 07:46:40', NULL, NULL),
	(76, 10, '4', 'Juli', 0, '2023-09-19 07:46:40', NULL, NULL),
	(77, 10, '1', 'Agustus', 0, '2023-09-19 07:46:40', NULL, NULL),
	(78, 10, '2', 'Agustus', 0, '2023-09-19 07:46:40', NULL, NULL),
	(79, 10, '3', 'Agustus', 0, '2023-09-19 07:46:40', NULL, NULL),
	(80, 10, '4', 'Agustus', 0, '2023-09-19 07:46:40', NULL, NULL),
	(81, 10, '1', 'September', 0, '2023-09-19 07:46:40', NULL, NULL),
	(82, 10, '2', 'September', 0, '2023-09-19 07:46:40', NULL, NULL),
	(83, 10, '3', 'September', 0, '2023-09-19 07:46:40', NULL, NULL),
	(84, 10, '4', 'September', 0, '2023-09-19 07:46:40', NULL, NULL),
	(85, 10, '1', 'Oktober', 0, '2023-09-19 07:46:40', NULL, NULL),
	(86, 10, '2', 'Oktober', 0, '2023-09-19 07:46:40', NULL, NULL),
	(87, 10, '3', 'Oktober', 0, '2023-09-19 07:46:40', NULL, NULL),
	(88, 10, '4', 'Oktober', 0, '2023-09-19 07:46:40', NULL, NULL),
	(89, 10, '1', 'November', 0, '2023-09-19 07:46:40', NULL, NULL),
	(90, 10, '2', 'November', 0, '2023-09-19 07:46:40', NULL, NULL),
	(91, 10, '3', 'November', 0, '2023-09-19 07:46:40', NULL, NULL),
	(92, 10, '4', 'November', 0, '2023-09-19 07:46:40', NULL, NULL),
	(93, 10, '1', 'Desember', 0, '2023-09-19 07:46:40', NULL, NULL),
	(94, 10, '2', 'Desember', 0, '2023-09-19 07:46:40', NULL, NULL),
	(95, 10, '3', 'Desember', 0, '2023-09-19 07:46:40', NULL, NULL),
	(96, 10, '4', 'Desember', 0, '2023-09-19 07:46:40', NULL, NULL);

-- Dumping structure for table emonev.rencana_pengambilan
CREATE TABLE IF NOT EXISTS `rencana_pengambilan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `subDpaId` bigint NOT NULL,
  `bulan` varchar(255) NOT NULL,
  `pagu` enum('1','2','3','4') NOT NULL,
  `realisasi` varchar(255) DEFAULT NULL,
  `keteranganPermasalahan` longtext,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subDpaId` (`subDpaId`),
  CONSTRAINT `rencana_pengambilan_ibfk_1` FOREIGN KEY (`subDpaId`) REFERENCES `sub_dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.rencana_pengambilan: ~12 rows (approximately)
INSERT INTO `rencana_pengambilan` (`id`, `subDpaId`, `bulan`, `pagu`, `realisasi`, `keteranganPermasalahan`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(4, 2, 'Januari', '4', '0', '', '2023-09-19 07:56:09', NULL, NULL),
	(5, 2, 'Januari', '1', '8000000', '', '2023-09-19 07:56:09', NULL, NULL),
	(6, 2, 'Januari', '2', '0', '', '2023-09-19 07:56:09', NULL, NULL),
	(7, 2, 'Januari', '3', '0', '', '2023-09-19 07:56:09', NULL, NULL),
	(8, 2, 'Februari', '1', '0', '-', '2023-09-18 18:57:04', NULL, NULL),
	(9, 2, 'Februari', '2', '0', '-', '2023-09-18 18:57:04', NULL, NULL),
	(10, 2, 'Februari', '3', '0', '-', '2023-09-18 18:57:04', NULL, NULL),
	(11, 2, 'Februari', '4', '0', '-', '2023-09-18 18:57:04', NULL, NULL),
	(12, 11, 'Januari', '1', '67000', '-', '2023-09-19 08:23:04', NULL, NULL),
	(13, 11, 'Januari', '2', '0', '-', '2023-09-19 08:23:04', NULL, NULL),
	(14, 11, 'Januari', '3', '900000', '-', '2023-09-19 08:23:04', NULL, NULL),
	(15, 11, 'Januari', '4', '0', '-', '2023-09-19 08:23:04', NULL, NULL);

-- Dumping structure for table emonev.rincian_objek_rekening
CREATE TABLE IF NOT EXISTS `rincian_objek_rekening` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `objekRekeningId` bigint NOT NULL,
  `kode` varchar(255) NOT NULL,
  `uraianAkun` text NOT NULL,
  `deskripsiAkun` text NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `objekRekeningId` (`objekRekeningId`),
  CONSTRAINT `rincian_objek_rekening_ibfk_1` FOREIGN KEY (`objekRekeningId`) REFERENCES `objek_rekening` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.rincian_objek_rekening: ~2 rows (approximately)
INSERT INTO `rincian_objek_rekening` (`id`, `objekRekeningId`, `kode`, `uraianAkun`, `deskripsiAkun`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, '5.1.02.01.01', 'Belanja Barang Pakai Habis', '-', '2023-08-24 15:23:17', '2023-09-15 12:07:10', NULL),
	(2, 4, '1', 'rincian objek', 'rincian objek', '2023-09-10 16:27:49', NULL, NULL);

-- Dumping structure for table emonev.role
CREATE TABLE IF NOT EXISTS `role` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `dinasId` bigint DEFAULT NULL,
  `role` varchar(100) NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dinasId` (`dinasId`),
  CONSTRAINT `role_ibfk_1` FOREIGN KEY (`dinasId`) REFERENCES `dinas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.role: ~2 rows (approximately)
INSERT INTO `role` (`id`, `dinasId`, `role`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, NULL, 'Admin Angaran', '2023-08-24 02:40:54', NULL, NULL),
	(2, NULL, 'Admin Pembangunan', '2023-08-24 02:40:54', NULL, NULL);

-- Dumping structure for table emonev.role_permission
CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `permissionId` bigint NOT NULL,
  `roleId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissionId` (`permissionId`),
  KEY `roleId` (`roleId`),
  CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`permissionId`) REFERENCES `permission` (`id`),
  CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`roleId`) REFERENCES `role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.role_permission: ~145 rows (approximately)
INSERT INTO `role_permission` (`id`, `permissionId`, `roleId`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 18, 1, '2023-08-24 02:40:54', NULL, NULL),
	(2, 9, 1, '2023-08-24 02:40:54', NULL, NULL),
	(3, 10, 1, '2023-08-24 02:40:54', NULL, NULL),
	(4, 11, 1, '2023-08-24 02:40:54', NULL, NULL),
	(5, 131, 1, '2023-08-24 02:40:54', NULL, NULL),
	(6, 132, 1, '2023-08-24 02:40:54', NULL, NULL),
	(7, 133, 1, '2023-08-24 02:40:54', NULL, NULL),
	(8, 134, 1, '2023-08-24 02:40:54', NULL, NULL),
	(9, 135, 1, '2023-08-24 02:40:54', NULL, NULL),
	(10, 136, 1, '2023-08-24 02:40:54', NULL, NULL),
	(11, 137, 1, '2023-08-24 02:40:54', NULL, NULL),
	(12, 138, 1, '2023-08-24 02:40:54', NULL, NULL),
	(13, 139, 1, '2023-08-24 02:40:54', NULL, NULL),
	(14, 140, 1, '2023-08-24 02:40:54', NULL, NULL),
	(15, 141, 1, '2023-08-24 02:40:54', NULL, NULL),
	(16, 142, 1, '2023-08-24 02:40:54', NULL, NULL),
	(17, 143, 1, '2023-08-24 02:40:54', NULL, NULL),
	(18, 144, 1, '2023-08-24 02:40:54', NULL, NULL),
	(19, 145, 1, '2023-08-24 02:40:54', NULL, NULL),
	(20, 146, 1, '2023-08-24 02:40:54', NULL, NULL),
	(21, 147, 1, '2023-08-24 02:40:54', NULL, NULL),
	(22, 148, 1, '2023-08-24 02:40:54', NULL, NULL),
	(23, 149, 1, '2023-08-24 02:40:54', NULL, NULL),
	(24, 150, 1, '2023-08-24 02:40:54', NULL, NULL),
	(25, 151, 1, '2023-08-24 02:40:54', NULL, NULL),
	(26, 152, 1, '2023-08-24 02:40:54', NULL, NULL),
	(27, 153, 1, '2023-08-24 02:40:54', NULL, NULL),
	(28, 154, 1, '2023-08-24 02:40:54', NULL, NULL),
	(29, 155, 1, '2023-08-24 02:40:54', NULL, NULL),
	(30, 156, 1, '2023-08-24 02:40:54', NULL, NULL),
	(31, 157, 1, '2023-08-24 02:40:54', NULL, NULL),
	(32, 158, 1, '2023-08-24 02:40:54', NULL, NULL),
	(33, 19, 1, '2023-08-24 02:40:54', NULL, NULL),
	(34, 20, 1, '2023-08-24 02:40:54', NULL, NULL),
	(35, 21, 1, '2023-08-24 02:40:54', NULL, NULL),
	(36, 22, 1, '2023-08-24 02:40:54', NULL, NULL),
	(37, 23, 1, '2023-08-24 02:40:54', NULL, NULL),
	(38, 24, 1, '2023-08-24 02:40:54', NULL, NULL),
	(39, 25, 1, '2023-08-24 02:40:54', NULL, NULL),
	(40, 26, 1, '2023-08-24 02:40:54', NULL, NULL),
	(41, 27, 1, '2023-08-24 02:40:54', NULL, NULL),
	(42, 28, 1, '2023-08-24 02:40:54', NULL, NULL),
	(43, 29, 1, '2023-08-24 02:40:54', NULL, NULL),
	(44, 30, 1, '2023-08-24 02:40:54', NULL, NULL),
	(45, 31, 1, '2023-08-24 02:40:54', NULL, NULL),
	(46, 32, 1, '2023-08-24 02:40:54', NULL, NULL),
	(47, 33, 1, '2023-08-24 02:40:54', NULL, NULL),
	(48, 34, 1, '2023-08-24 02:40:54', NULL, NULL),
	(49, 35, 1, '2023-08-24 02:40:54', NULL, NULL),
	(50, 36, 1, '2023-08-24 02:40:54', NULL, NULL),
	(51, 37, 1, '2023-08-24 02:40:54', NULL, NULL),
	(52, 38, 1, '2023-08-24 02:40:54', NULL, NULL),
	(53, 39, 1, '2023-08-24 02:40:54', NULL, NULL),
	(54, 40, 1, '2023-08-24 02:40:54', NULL, NULL),
	(55, 41, 1, '2023-08-24 02:40:54', NULL, NULL),
	(56, 42, 1, '2023-08-24 02:40:54', NULL, NULL),
	(57, 43, 1, '2023-08-24 02:40:54', NULL, NULL),
	(58, 44, 1, '2023-08-24 02:40:54', NULL, NULL),
	(59, 45, 1, '2023-08-24 02:40:54', NULL, NULL),
	(60, 46, 1, '2023-08-24 02:40:54', NULL, NULL),
	(61, 47, 1, '2023-08-24 02:40:54', NULL, NULL),
	(62, 48, 1, '2023-08-24 02:40:54', NULL, NULL),
	(63, 49, 1, '2023-08-24 02:40:54', NULL, NULL),
	(64, 50, 1, '2023-08-24 02:40:54', NULL, NULL),
	(65, 51, 1, '2023-08-24 02:40:54', NULL, NULL),
	(66, 52, 1, '2023-08-24 02:40:54', NULL, NULL),
	(67, 53, 1, '2023-08-24 02:40:54', NULL, NULL),
	(68, 54, 1, '2023-08-24 02:40:54', NULL, NULL),
	(69, 55, 1, '2023-08-24 02:40:54', NULL, NULL),
	(70, 56, 1, '2023-08-24 02:40:54', NULL, NULL),
	(71, 57, 1, '2023-08-24 02:40:54', NULL, NULL),
	(72, 58, 1, '2023-08-24 02:40:54', NULL, NULL),
	(73, 59, 1, '2023-08-24 02:40:54', NULL, NULL),
	(74, 60, 1, '2023-08-24 02:40:54', NULL, NULL),
	(75, 61, 1, '2023-08-24 02:40:54', NULL, NULL),
	(76, 62, 1, '2023-08-24 02:40:54', NULL, NULL),
	(77, 63, 1, '2023-08-24 02:40:54', NULL, NULL),
	(78, 64, 1, '2023-08-24 02:40:54', NULL, NULL),
	(79, 65, 1, '2023-08-24 02:40:54', NULL, NULL),
	(80, 66, 1, '2023-08-24 02:40:54', NULL, NULL),
	(81, 67, 1, '2023-08-24 02:40:54', NULL, NULL),
	(82, 68, 1, '2023-08-24 02:40:54', NULL, NULL),
	(83, 69, 1, '2023-08-24 02:40:54', NULL, NULL),
	(84, 70, 1, '2023-08-24 02:40:54', NULL, NULL),
	(85, 71, 1, '2023-08-24 02:40:54', NULL, NULL),
	(86, 72, 1, '2023-08-24 02:40:54', NULL, NULL),
	(87, 73, 1, '2023-08-24 02:40:54', NULL, NULL),
	(88, 74, 1, '2023-08-24 02:40:54', NULL, NULL),
	(89, 75, 1, '2023-08-24 02:40:54', NULL, NULL),
	(90, 76, 1, '2023-08-24 02:40:54', NULL, NULL),
	(91, 77, 1, '2023-08-24 02:40:54', NULL, NULL),
	(92, 78, 1, '2023-08-24 02:40:54', NULL, NULL),
	(93, 79, 1, '2023-08-24 02:40:54', NULL, NULL),
	(94, 80, 1, '2023-08-24 02:40:54', NULL, NULL),
	(95, 81, 1, '2023-08-24 02:40:54', NULL, NULL),
	(96, 82, 1, '2023-08-24 02:40:54', NULL, NULL),
	(97, 83, 1, '2023-08-24 02:40:54', NULL, NULL),
	(98, 84, 1, '2023-08-24 02:40:54', NULL, NULL),
	(99, 85, 1, '2023-08-24 02:40:54', NULL, NULL),
	(100, 86, 1, '2023-08-24 02:40:54', NULL, NULL),
	(101, 87, 1, '2023-08-24 02:40:54', NULL, NULL),
	(102, 88, 1, '2023-08-24 02:40:54', NULL, NULL),
	(103, 89, 1, '2023-08-24 02:40:54', NULL, NULL),
	(104, 90, 1, '2023-08-24 02:40:54', NULL, NULL),
	(105, 91, 1, '2023-08-24 02:40:54', NULL, NULL),
	(106, 92, 1, '2023-08-24 02:40:54', NULL, NULL),
	(107, 93, 1, '2023-08-24 02:40:54', NULL, NULL),
	(108, 94, 1, '2023-08-24 02:40:54', NULL, NULL),
	(109, 95, 1, '2023-08-24 02:40:54', NULL, NULL),
	(110, 96, 1, '2023-08-24 02:40:54', NULL, NULL),
	(111, 97, 1, '2023-08-24 02:40:54', NULL, NULL),
	(112, 98, 1, '2023-08-24 02:40:54', NULL, NULL),
	(113, 99, 1, '2023-08-24 02:40:54', NULL, NULL),
	(114, 100, 1, '2023-08-24 02:40:54', NULL, NULL),
	(115, 101, 1, '2023-08-24 02:40:54', NULL, NULL),
	(116, 102, 1, '2023-08-24 02:40:54', NULL, NULL),
	(117, 103, 1, '2023-08-24 02:40:54', NULL, NULL),
	(118, 104, 1, '2023-08-24 02:40:54', NULL, NULL),
	(119, 105, 1, '2023-08-24 02:40:54', NULL, NULL),
	(120, 106, 1, '2023-08-24 02:40:54', NULL, NULL),
	(121, 107, 1, '2023-08-24 02:40:54', NULL, NULL),
	(122, 108, 1, '2023-08-24 02:40:54', NULL, NULL),
	(123, 109, 1, '2023-08-24 02:40:54', NULL, NULL),
	(124, 110, 1, '2023-08-24 02:40:54', NULL, NULL),
	(125, 111, 1, '2023-08-24 02:40:54', NULL, NULL),
	(126, 112, 1, '2023-08-24 02:40:54', NULL, NULL),
	(127, 113, 1, '2023-08-24 02:40:54', NULL, NULL),
	(128, 114, 1, '2023-08-24 02:40:54', NULL, NULL),
	(129, 115, 1, '2023-08-24 02:40:54', NULL, NULL),
	(130, 116, 1, '2023-08-24 02:40:54', NULL, NULL),
	(131, 117, 1, '2023-08-24 02:40:54', NULL, NULL),
	(132, 118, 1, '2023-08-24 02:40:54', NULL, NULL),
	(133, 119, 1, '2023-08-24 02:40:54', NULL, NULL),
	(134, 120, 1, '2023-08-24 02:40:54', NULL, NULL),
	(135, 121, 1, '2023-08-24 02:40:54', NULL, NULL),
	(136, 122, 1, '2023-08-24 02:40:54', NULL, NULL),
	(137, 123, 1, '2023-08-24 02:40:54', NULL, NULL),
	(138, 124, 1, '2023-08-24 02:40:54', NULL, NULL),
	(139, 125, 1, '2023-08-24 02:40:54', NULL, NULL),
	(140, 126, 1, '2023-08-24 02:40:54', NULL, NULL),
	(141, 127, 1, '2023-08-24 02:40:54', NULL, NULL),
	(142, 128, 1, '2023-08-24 02:40:54', NULL, NULL),
	(143, 129, 1, '2023-08-24 02:40:54', NULL, NULL),
	(144, 130, 1, '2023-08-24 02:40:54', NULL, NULL),
	(145, 18, 2, '2023-08-24 02:40:54', NULL, NULL),
	(147, 12, 2, '2023-08-24 02:40:54', NULL, NULL),
	(148, 13, 2, '2023-08-24 02:40:54', NULL, NULL),
	(149, 14, 2, '2023-08-24 02:40:54', NULL, NULL);

-- Dumping structure for table emonev.role_user
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userId` bigint NOT NULL,
  `roleId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `roleId` (`roleId`),
  CONSTRAINT `role_user_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`),
  CONSTRAINT `role_user_ibfk_2` FOREIGN KEY (`roleId`) REFERENCES `role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.role_user: ~2 rows (approximately)
INSERT INTO `role_user` (`id`, `userId`, `roleId`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 3, 1, '2023-08-24 02:40:54', NULL, NULL),
	(2, 4, 2, '2023-08-24 02:40:54', NULL, NULL);

-- Dumping structure for table emonev.satuan
CREATE TABLE IF NOT EXISTS `satuan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `satuan` varchar(255) NOT NULL,
  `pembangunan` int NOT NULL DEFAULT '0',
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.satuan: ~1 rows (approximately)
INSERT INTO `satuan` (`id`, `satuan`, `pembangunan`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 'Dokumen Update', 0, '2023-08-23 15:58:10', '2023-08-23 16:00:47', NULL);

-- Dumping structure for table emonev.sub_dpa
CREATE TABLE IF NOT EXISTS `sub_dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `dpaId` bigint NOT NULL,
  `subKegiatanId` bigint NOT NULL,
  `sumberDanaId` bigint NOT NULL,
  `lokasi` text NOT NULL,
  `target` int NOT NULL,
  `waktuPelaksanaan` varchar(255) NOT NULL,
  `keterangan` text,
  `jumlahAnggaran` varchar(255) DEFAULT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dpaId` (`dpaId`),
  CONSTRAINT `sub_dpa_ibfk_1` FOREIGN KEY (`dpaId`) REFERENCES `dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.sub_dpa: ~10 rows (approximately)
INSERT INTO `sub_dpa` (`id`, `dpaId`, `subKegiatanId`, `sumberDanaId`, `lokasi`, `target`, `waktuPelaksanaan`, `keterangan`, `jumlahAnggaran`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(2, 9, 1, 1, 'asd', 100, 'asdasd', '', '635850000', '2023-09-14 15:38:41', '2023-09-17 15:23:20', NULL),
	(3, 9, 1, 1, 'asd', 100, 'asdasd', '', '2160000000', '2023-09-14 15:53:34', NULL, NULL),
	(4, 9, 1, 1, 'asd', 123, 'asdasd', '-', '25309000000', '2023-09-14 15:54:58', NULL, NULL),
	(5, 9, 1, 1, 'asd', 100, 'asdasd', '', '198000000', '2023-09-14 18:55:01', NULL, NULL),
	(6, 9, 1, 1, 'asd', 100, 'asdasd', '', '168000000', '2023-09-14 19:04:51', NULL, NULL),
	(7, 9, 1, 1, 'asd', 100, 'asdasd', '', '168000000', '2023-09-14 19:05:20', NULL, NULL),
	(8, 9, 1, 1, 'asd', 100, 'asdasd', '', '200160000000', '2023-09-14 19:06:16', NULL, '2023-09-17 07:04:30'),
	(9, 9, 1, 1, 'asd', 123, 'asdasd', '', '360000000', '2023-09-16 20:10:50', NULL, NULL),
	(10, 9, 1, 1, 'asd', 100, 'asdasd', '', '471001150000', '2023-09-17 15:18:57', NULL, NULL),
	(11, 10, 1, 1, 'asfdghj', 100, 'ggdfg', '', '6164653', '2023-09-19 07:09:54', '2023-09-19 07:10:42', NULL);

-- Dumping structure for table emonev.sub_kegiatan
CREATE TABLE IF NOT EXISTS `sub_kegiatan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `kegiatanId` bigint NOT NULL,
  `satuanId` bigint NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nomenklatur` longtext NOT NULL,
  `kinerja` longtext NOT NULL,
  `indikator` longtext NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kegiatanId` (`kegiatanId`),
  KEY `satuanId` (`satuanId`),
  CONSTRAINT `sub_kegiatan_ibfk_1` FOREIGN KEY (`kegiatanId`) REFERENCES `kegiatan` (`id`),
  CONSTRAINT `sub_kegiatan_ibfk_2` FOREIGN KEY (`satuanId`) REFERENCES `satuan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.sub_kegiatan: ~2 rows (approximately)
INSERT INTO `sub_kegiatan` (`id`, `kegiatanId`, `satuanId`, `kode`, `nomenklatur`, `kinerja`, `indikator`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, 1, '7.01.01.2.01.01', 'Penyusunan Dokumen Perencanaan Perangkat Daerah', 'asdasaaaaaa', 'asdasd', '2023-08-24 02:40:54', '2023-09-17 21:18:08', NULL),
	(2, 3, 1, '1', 'ini  update nya', 'asdasd', 'asdasdsd  sss', '2023-09-10 18:16:32', '2023-09-10 18:18:01', NULL);

-- Dumping structure for table emonev.sub_rincian_objek_rekening
CREATE TABLE IF NOT EXISTS `sub_rincian_objek_rekening` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rincianObjekRekeningId` bigint NOT NULL,
  `kode` varchar(255) NOT NULL,
  `uraianAkun` text NOT NULL,
  `deskripsiAkun` text NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rincianObjekRekeningId` (`rincianObjekRekeningId`),
  CONSTRAINT `sub_rincian_objek_rekening_ibfk_1` FOREIGN KEY (`rincianObjekRekeningId`) REFERENCES `rincian_objek_rekening` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.sub_rincian_objek_rekening: ~0 rows (approximately)
INSERT INTO `sub_rincian_objek_rekening` (`id`, `rincianObjekRekeningId`, `kode`, `uraianAkun`, `deskripsiAkun`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, '5.1.02.01.01.0026', ' Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak', '-', '2023-08-24 15:27:50', '2023-09-15 12:07:34', NULL),
	(2, 1, '5.1.02.01.01.0052', ' Belanja Makanan dan Minuman Rapat ', '-', '2023-09-15 12:08:19', NULL, NULL);

-- Dumping structure for table emonev.sumber_dana
CREATE TABLE IF NOT EXISTS `sumber_dana` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `sumberDana` varchar(255) NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.sumber_dana: ~0 rows (approximately)
INSERT INTO `sumber_dana` (`id`, `sumberDana`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 'Hibah Update', '2023-08-23 16:18:28', '2023-08-23 16:18:53', NULL);

-- Dumping structure for table emonev.tahun
CREATE TABLE IF NOT EXISTS `tahun` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tahun` varchar(255) NOT NULL,
  `active` int NOT NULL DEFAULT '0',
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.tahun: ~1 rows (approximately)
INSERT INTO `tahun` (`id`, `tahun`, `active`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, '2023', 1, '2023-08-23 13:34:39', '2023-08-23 14:31:31', NULL);

-- Dumping structure for table emonev.unit
CREATE TABLE IF NOT EXISTS `unit` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `organisasiId` bigint NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nomenklatur` longtext NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organisasiId` (`organisasiId`),
  CONSTRAINT `unit_ibfk_1` FOREIGN KEY (`organisasiId`) REFERENCES `organisasi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.unit: ~3 rows (approximately)
INSERT INTO `unit` (`id`, `organisasiId`, `kode`, `nomenklatur`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, '1', 'update', '2023-08-24 16:09:59', '2023-09-10 13:37:32', '2023-09-10 14:07:49'),
	(2, 2, '1.2', 'ini  update', '2023-09-10 13:42:11', NULL, '2023-09-10 14:06:28'),
	(3, 2, '1.2', 'ini add', '2023-09-10 13:42:32', NULL, NULL);

-- Dumping structure for table emonev.urusan
CREATE TABLE IF NOT EXISTS `urusan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `nomenklatur` longtext NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.urusan: ~0 rows (approximately)
INSERT INTO `urusan` (`id`, `kode`, `nomenklatur`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, '7', 'UNSUR KEWILAYAHAN', '2023-08-23 19:43:52', '2023-09-17 21:15:41', NULL),
	(2, '24', 'asdasd', '2023-09-10 17:15:20', '2023-09-10 17:15:24', NULL);

-- Dumping structure for table emonev.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.user: ~3 rows (approximately)
INSERT INTO `user` (`id`, `name`, `email`, `password`, `status`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 'SuperAdmin', 'admin@mail.com', '$2y$10$wjQdd1Rd0uyyfMdOsQoV9.UMnpVJ2KTawKO1wz1gCSpuEge3/OeKm', 0, '123459372', NULL, NULL),
	(3, 'Diki Rahmad Sandi', 'diki@mail.com', '$2y$10$wjQdd1Rd0uyyfMdOsQoV9.UMnpVJ2KTawKO1wz1gCSpuEge3/OeKm', 1, '123459372', NULL, NULL),
	(4, 'Diki Pembangunan', 'diki2@mail.com', '$2y$10$wjQdd1Rd0uyyfMdOsQoV9.UMnpVJ2KTawKO1wz1gCSpuEge3/OeKm', 1, '123459372', NULL, NULL);

-- Dumping structure for table emonev.user_dinas
CREATE TABLE IF NOT EXISTS `user_dinas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userId` bigint NOT NULL,
  `dinasId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `dinasId` (`dinasId`),
  CONSTRAINT `user_dinas_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`),
  CONSTRAINT `user_dinas_ibfk_2` FOREIGN KEY (`dinasId`) REFERENCES `dinas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.user_dinas: ~2 rows (approximately)
INSERT INTO `user_dinas` (`id`, `userId`, `dinasId`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 3, 3, '2023-03-03 12:12:12', NULL, NULL),
	(2, 4, 3, '2023-03-03 12:12:12', NULL, NULL);

-- Dumping structure for table emonev.user_permission
CREATE TABLE IF NOT EXISTS `user_permission` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userId` bigint NOT NULL,
  `permissionId` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `permissionId` (`permissionId`),
  CONSTRAINT `user_permission_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`),
  CONSTRAINT `user_permission_ibfk_2` FOREIGN KEY (`permissionId`) REFERENCES `permission` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table emonev.user_permission: ~158 rows (approximately)
INSERT INTO `user_permission` (`id`, `userId`, `permissionId`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, 1, '1692726391209', NULL, NULL),
	(2, 1, 2, '1692726391209', NULL, NULL),
	(3, 1, 3, '1692726391209', NULL, NULL),
	(4, 1, 4, '1692726391209', NULL, NULL),
	(5, 1, 5, '1692726391209', NULL, NULL),
	(6, 1, 6, '1692726391209', NULL, NULL),
	(7, 1, 7, '1692726391209', NULL, NULL),
	(8, 1, 8, '1692726391209', NULL, NULL),
	(9, 1, 9, '1692726391209', NULL, NULL),
	(10, 1, 10, '1692726391209', NULL, NULL),
	(11, 1, 11, '1692726391209', NULL, NULL),
	(12, 1, 12, '1692726391209', NULL, NULL),
	(13, 1, 13, '1692726391209', NULL, NULL),
	(14, 1, 14, '1692726391209', NULL, NULL),
	(15, 1, 15, '1692726391209', NULL, NULL),
	(16, 1, 16, '1692726391209', NULL, NULL),
	(17, 1, 17, '1692726391209', NULL, NULL),
	(18, 1, 18, '1692726391209', NULL, NULL),
	(19, 1, 19, '1692726391209', NULL, NULL),
	(20, 1, 20, '1692726391209', NULL, NULL),
	(21, 1, 21, '1692726391209', NULL, NULL),
	(22, 1, 22, '1692726391209', NULL, NULL),
	(23, 1, 23, '1692726391209', NULL, NULL),
	(24, 1, 24, '1692726391209', NULL, NULL),
	(25, 1, 25, '1692726391209', NULL, NULL),
	(26, 1, 26, '1692726391209', NULL, NULL),
	(27, 1, 27, '1692726391209', NULL, NULL),
	(28, 1, 28, '1692726391209', NULL, NULL),
	(29, 1, 29, '1692726391209', NULL, NULL),
	(30, 1, 30, '1692726391209', NULL, NULL),
	(31, 1, 31, '1692726391209', NULL, NULL),
	(32, 1, 32, '1692726391209', NULL, NULL),
	(33, 1, 33, '1692726391209', NULL, NULL),
	(34, 1, 34, '1692726391209', NULL, NULL),
	(35, 1, 35, '1692726391209', NULL, NULL),
	(36, 1, 36, '1692726391209', NULL, NULL),
	(37, 1, 37, '1692726391209', NULL, NULL),
	(38, 1, 38, '1692726391209', NULL, NULL),
	(39, 1, 39, '1692726391209', NULL, NULL),
	(40, 1, 40, '1692726391209', NULL, NULL),
	(41, 1, 41, '1692726391209', NULL, NULL),
	(42, 1, 42, '1692726391209', NULL, NULL),
	(43, 1, 43, '1692726391209', NULL, NULL),
	(44, 1, 44, '1692726391209', NULL, NULL),
	(45, 1, 45, '1692726391209', NULL, NULL),
	(46, 1, 46, '1692726391209', NULL, NULL),
	(47, 1, 47, '1692726391209', NULL, NULL),
	(48, 1, 48, '1692726391209', NULL, NULL),
	(49, 1, 49, '1692726391209', NULL, NULL),
	(50, 1, 50, '1692726391209', NULL, NULL),
	(51, 1, 51, '1692726391209', NULL, NULL),
	(52, 1, 52, '1692726391209', NULL, NULL),
	(53, 1, 53, '1692726391209', NULL, NULL),
	(54, 1, 54, '1692726391209', NULL, NULL),
	(55, 1, 55, '1692726391209', NULL, NULL),
	(56, 1, 56, '1692726391209', NULL, NULL),
	(57, 1, 57, '1692726391209', NULL, NULL),
	(58, 1, 58, '1692726391209', NULL, NULL),
	(59, 1, 59, '1692726391209', NULL, NULL),
	(60, 1, 60, '1692726391209', NULL, NULL),
	(61, 1, 61, '1692726391209', NULL, NULL),
	(62, 1, 62, '1692726391209', NULL, NULL),
	(63, 1, 63, '1692726391209', NULL, NULL),
	(64, 1, 64, '1692726391209', NULL, NULL),
	(65, 1, 65, '1692726391209', NULL, NULL),
	(66, 1, 66, '1692726391209', NULL, NULL),
	(67, 1, 67, '1692726391209', NULL, NULL),
	(68, 1, 68, '1692726391209', NULL, NULL),
	(69, 1, 69, '1692726391209', NULL, NULL),
	(70, 1, 70, '1692726391209', NULL, NULL),
	(71, 1, 71, '1692726391209', NULL, NULL),
	(72, 1, 72, '1692726391209', NULL, NULL),
	(73, 1, 73, '1692726391209', NULL, NULL),
	(74, 1, 74, '1692726391209', NULL, NULL),
	(75, 1, 75, '1692726391209', NULL, NULL),
	(76, 1, 76, '1692726391209', NULL, NULL),
	(77, 1, 77, '1692726391209', NULL, NULL),
	(78, 1, 78, '1692726391209', NULL, NULL),
	(79, 1, 79, '1692726391209', NULL, NULL),
	(80, 1, 80, '1692726391209', NULL, NULL),
	(81, 1, 81, '1692726391209', NULL, NULL),
	(82, 1, 82, '1692726391209', NULL, NULL),
	(83, 1, 83, '1692726391209', NULL, NULL),
	(84, 1, 84, '1692726391209', NULL, NULL),
	(85, 1, 85, '1692726391209', NULL, NULL),
	(86, 1, 86, '1692726391209', NULL, NULL),
	(87, 1, 87, '1692726391209', NULL, NULL),
	(88, 1, 88, '1692726391209', NULL, NULL),
	(89, 1, 89, '1692726391209', NULL, NULL),
	(90, 1, 90, '1692726391209', NULL, NULL),
	(91, 1, 91, '1692726391209', NULL, NULL),
	(92, 1, 92, '1692726391209', NULL, NULL),
	(93, 1, 93, '1692726391209', NULL, NULL),
	(94, 1, 94, '1692726391209', NULL, NULL),
	(95, 1, 95, '1692726391209', NULL, NULL),
	(96, 1, 96, '1692726391209', NULL, NULL),
	(97, 1, 97, '1692726391209', NULL, NULL),
	(98, 1, 98, '1692726391209', NULL, NULL),
	(99, 1, 99, '1692726391209', NULL, NULL),
	(100, 1, 100, '1692726391209', NULL, NULL),
	(101, 1, 101, '1692726391209', NULL, NULL),
	(102, 1, 102, '1692726391209', NULL, NULL),
	(103, 1, 103, '1692726391209', NULL, NULL),
	(104, 1, 104, '1692726391209', NULL, NULL),
	(105, 1, 105, '1692726391209', NULL, NULL),
	(106, 1, 106, '1692726391209', NULL, NULL),
	(107, 1, 107, '1692726391209', NULL, NULL),
	(108, 1, 108, '1692726391209', NULL, NULL),
	(109, 1, 109, '1692726391209', NULL, NULL),
	(110, 1, 110, '1692726391209', NULL, NULL),
	(111, 1, 111, '1692726391209', NULL, NULL),
	(112, 1, 112, '1692726391209', NULL, NULL),
	(113, 1, 113, '1692726391209', NULL, NULL),
	(114, 1, 114, '1692726391209', NULL, NULL),
	(115, 1, 115, '1692726391209', NULL, NULL),
	(116, 1, 116, '1692726391209', NULL, NULL),
	(117, 1, 117, '1692726391209', NULL, NULL),
	(118, 1, 118, '1692726391209', NULL, NULL),
	(119, 1, 119, '1692726391209', NULL, NULL),
	(120, 1, 120, '1692726391209', NULL, NULL),
	(121, 1, 121, '1692726391209', NULL, NULL),
	(122, 1, 122, '1692726391209', NULL, NULL),
	(123, 1, 123, '1692726391209', NULL, NULL),
	(124, 1, 124, '1692726391209', NULL, NULL),
	(125, 1, 125, '1692726391209', NULL, NULL),
	(126, 1, 126, '1692726391209', NULL, NULL),
	(127, 1, 127, '1692726391209', NULL, NULL),
	(128, 1, 128, '1692726391209', NULL, NULL),
	(129, 1, 129, '1692726391209', NULL, NULL),
	(130, 1, 130, '1692726391209', NULL, NULL),
	(131, 1, 131, '1692726391209', NULL, NULL),
	(132, 1, 132, '1692726391209', NULL, NULL),
	(133, 1, 133, '1692726391209', NULL, NULL),
	(134, 1, 134, '1692726391209', NULL, NULL),
	(135, 1, 135, '1692726391209', NULL, NULL),
	(136, 1, 136, '1692726391209', NULL, NULL),
	(137, 1, 137, '1692726391209', NULL, NULL),
	(138, 1, 138, '1692726391209', NULL, NULL),
	(139, 1, 139, '1692726391209', NULL, NULL),
	(140, 1, 140, '1692726391209', NULL, NULL),
	(141, 1, 141, '1692726391209', NULL, NULL),
	(142, 1, 142, '1692726391209', NULL, NULL),
	(143, 1, 143, '1692726391209', NULL, NULL),
	(144, 1, 144, '1692726391209', NULL, NULL),
	(145, 1, 145, '1692726391209', NULL, NULL),
	(146, 1, 146, '1692726391209', NULL, NULL),
	(147, 1, 147, '1692726391209', NULL, NULL),
	(148, 1, 148, '1692726391209', NULL, NULL),
	(149, 1, 149, '1692726391209', NULL, NULL),
	(150, 1, 150, '1692726391209', NULL, NULL),
	(151, 1, 151, '1692726391209', NULL, NULL),
	(152, 1, 152, '1692726391209', NULL, NULL),
	(153, 1, 153, '1692726391209', NULL, NULL),
	(154, 1, 154, '1692726391209', NULL, NULL),
	(155, 1, 155, '1692726391209', NULL, NULL),
	(156, 1, 156, '1692726391209', NULL, NULL),
	(157, 1, 157, '1692726391209', NULL, NULL),
	(158, 1, 158, '1692726391209', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
