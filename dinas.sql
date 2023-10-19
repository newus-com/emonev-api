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

-- Dumping structure for view dinas_4.akun_rekening
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `akun_rekening` (
	`id` BIGINT(19) NOT NULL,
	`kode` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`uraianAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`deskripsiAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.bidang
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `bidang` (
	`id` BIGINT(19) NOT NULL,
	`urusanId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`nomenklatur` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for table dinas_4.detail_ket_sub_dpa
CREATE TABLE IF NOT EXISTS `detail_ket_sub_dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `ketSubDpaId` bigint NOT NULL,
  `satuanId` bigint NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `spesifikasi` varchar(255) NOT NULL,
  `koefisien` int NOT NULL,
  `harga` bigint NOT NULL,
  `ppn` bigint NOT NULL,
  `jumlah` bigint NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ketSubDpaId` (`ketSubDpaId`),
  CONSTRAINT `detail_ket_sub_dpa_ibfk_1` FOREIGN KEY (`ketSubDpaId`) REFERENCES `ket_sub_dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dinas_4.detail_ket_sub_dpa: ~3 rows (approximately)
INSERT INTO `detail_ket_sub_dpa` (`id`, `ketSubDpaId`, `satuanId`, `uraian`, `spesifikasi`, `koefisien`, `harga`, `ppn`, `jumlah`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, 1, 'ini uraian', 'ini spek', 4, 5000, 11, 22200, '2023-08-28 12:02:16', '2023-09-01 13:38:00', NULL),
	(2, 1, 1, 'ini uraian', 'ini spek', 4, 50000, 11, 222000, '2023-09-01 13:34:45', NULL, NULL),
	(3, 1, 1, 'ini uraian', 'ini spek', 4, 5000000, 11, 22200000, '2023-09-01 13:35:18', NULL, NULL);

-- Dumping structure for table dinas_4.detail_rencana_pembangunan
CREATE TABLE IF NOT EXISTS `detail_rencana_pembangunan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rencanaPembangunanId` bigint NOT NULL,
  `komponenPembangunanId` bigint DEFAULT NULL,
  `volume` int DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `persentase` int NOT NULL DEFAULT '0',
  `riil` enum('sudah','belum') DEFAULT NULL,
  `keterangan` longtext,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rencanaPembangunanId` (`rencanaPembangunanId`),
  CONSTRAINT `detail_rencana_pembangunan_ibfk_1` FOREIGN KEY (`rencanaPembangunanId`) REFERENCES `rencana_pembangunan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dinas_4.detail_rencana_pembangunan: ~1 rows (approximately)
INSERT INTO `detail_rencana_pembangunan` (`id`, `rencanaPembangunanId`, `komponenPembangunanId`, `volume`, `satuan`, `harga`, `persentase`, `riil`, `keterangan`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, 3, 100, 'ntah', 100000, 100, 'sudah', '-', '2023-09-02 13:04:05', '2023-09-02 16:41:31', NULL);

-- Dumping structure for table dinas_4.dokumentasi_pekerjaan_pembangunan
CREATE TABLE IF NOT EXISTS `dokumentasi_pekerjaan_pembangunan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `detailRencanaPembangunanId` bigint NOT NULL,
  `dokumentasi` text NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detailRencanaPembangunanId` (`detailRencanaPembangunanId`),
  CONSTRAINT `dokumentasi_pekerjaan_pembangunan_ibfk_1` FOREIGN KEY (`detailRencanaPembangunanId`) REFERENCES `detail_rencana_pembangunan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dinas_4.dokumentasi_pekerjaan_pembangunan: ~0 rows (approximately)

-- Dumping structure for table dinas_4.dpa
CREATE TABLE IF NOT EXISTS `dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `noDpa` varchar(255) NOT NULL,
  `tahunId` bigint NOT NULL,
  `urusanId` bigint NOT NULL,
  `bidangId` bigint NOT NULL,
  `programId` bigint NOT NULL,
  `kegiatanId` bigint NOT NULL,
  `organisasiId` bigint NOT NULL,
  `unitId` bigint NOT NULL,
  `sasaranProgram` text,
  `capaianProgram` longtext,
  `alokasiTahun` longtext,
  `indikatorKinerja` longtext,
  `kelompokSasaranKegiatan` longtext,
  `rencanaPenarikan` longtext,
  `timAnggaran` longtext,
  `ttdDpa` longtext,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dinas_4.dpa: ~0 rows (approximately)
INSERT INTO `dpa` (`id`, `noDpa`, `tahunId`, `urusanId`, `bidangId`, `programId`, `kegiatanId`, `organisasiId`, `unitId`, `sasaranProgram`, `capaianProgram`, `alokasiTahun`, `indikatorKinerja`, `kelompokSasaranKegiatan`, `rencanaPenarikan`, `timAnggaran`, `ttdDpa`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 'ASD-003', 1, 1, 1, 1, 1, 1, 1, '', '{\n    "indikator": "asd",\n    "target": "asd"\n}', '[\n    {\n        "tahun": "2022",\n        "jumlah": "23000000"\n    },\n    {\n        "tahun": "2023",\n        "jumlah": "23000000"\n    }\n]', '[\n    {\n        "indikator": "Capaian Kegiatan",\n        "tolakUkur": "dfbksjfnsdf",\n        "kinerja": "fsdfsdf"\n    },\n    {\n        "indikator": "Masukan",\n        "tolakUkur": "dfbksjfnsdf",\n        "kinerja": "fsdfsdf"\n    },\n    {\n        "indikator": "Keluaran",\n        "tolakUkur": "dfbksjfnsdf",\n        "kinerja": "fsdfsdf"\n    },\n    {\n        "indikator": "Hasil",\n        "tolakUkur": "dfbksjfnsdf",\n        "kinerja": "fsdfsdf"\n    }\n]', '', '[\n    {\n        "bulan": "Januari",\n        "jumlah": "12000"\n    },\n    {\n        "bulan": "Februari",\n        "jumlah": "4234234"\n    },\n    {\n        "bulan": "Maret",\n        "jumlah": "67"\n    },\n    {\n        "bulan": "April",\n        "jumlah": "676"\n    },\n    {\n        "bulan": "Mei",\n        "jumlah": "7667"\n    },\n    {\n        "bulan": "Juni",\n        "jumlah": "0"\n    },\n    {\n        "bulan": "Juli",\n        "jumlah": "0"\n    },\n    {\n        "bulan": "Agustus",\n        "jumlah": "0"\n    },\n    {\n        "bulan": "September",\n        "jumlah": "0"\n    },\n    {\n        "bulan": "Oktober",\n        "jumlah": "0"\n    },\n    {\n        "bulan": "November",\n        "jumlah": "0"\n    },\n    {\n        "bulan": "Desember",\n        "jumlah": "0"\n    }\n]', '[\n    {\n        "nama": "Repellendus Laborum",\n        "nip": "234566",\n        "jabatan": "yghjgbhj"\n    },\n    {\n        "nama": "gsavgdja",\n        "nip": "Ipsam possimus temp",\n        "jabatan": "Aliquid pariatur Pr"\n    }\n]', '{\n    "headerTtd": {\n        "kota": "Kalianda",\n        "tanggal": "2023-05-04"\n    },\n    "bodyTtd": [\n        {\n            "jabatanPejabat": "Aut exercitationem e",\n            "nip": "Ipsam possimus temp"\n        },\n        {\n            "jabatanPejabat": "Aut exercitationem e",\n            "nip": "Ipsam possimus temp"\n        }\n    ]\n}', '2023-08-31 08:58:06', '2023-08-28 12:40:37', NULL);

-- Dumping structure for view dinas_4.jenis_rekening
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `jenis_rekening` (
	`id` BIGINT(19) NOT NULL,
	`kelompokRekeningId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`uraianAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`deskripsiAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.kegiatan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `kegiatan` (
	`id` BIGINT(19) NOT NULL,
	`programId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`nomenklatur` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.kelompok_rekening
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `kelompok_rekening` (
	`id` BIGINT(19) NOT NULL,
	`akunRekeningId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`uraianAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`deskripsiAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for table dinas_4.ket_sub_dpa
CREATE TABLE IF NOT EXISTS `ket_sub_dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `subDpaId` bigint NOT NULL,
  `subRincianObjekRekeningId` bigint NOT NULL,
  `jumlahAnggaran` bigint DEFAULT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subDpaId` (`subDpaId`),
  CONSTRAINT `ket_sub_dpa_ibfk_1` FOREIGN KEY (`subDpaId`) REFERENCES `ket_sub_dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dinas_4.ket_sub_dpa: ~0 rows (approximately)
INSERT INTO `ket_sub_dpa` (`id`, `subDpaId`, `subRincianObjekRekeningId`, `jumlahAnggaran`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, 1, 8000000, '2023-08-28 09:26:59', '2023-09-01 13:47:21', NULL);

-- Dumping structure for view dinas_4.komponen_pembangunan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `komponen_pembangunan` (
	`id` BIGINT(19) NOT NULL,
	`parentId` BIGINT(19) NULL,
	`komponen` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.objek_rekening
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `objek_rekening` (
	`id` BIGINT(19) NOT NULL,
	`jenisRekeningId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`uraianAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`deskripsiAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.organisasi
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `organisasi` (
	`id` BIGINT(19) NOT NULL,
	`bidangId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`nomenklatur` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.program
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `program` (
	`id` BIGINT(19) NOT NULL,
	`bidangId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`nomenklatur` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for table dinas_4.rencana_pembangunan
CREATE TABLE IF NOT EXISTS `rencana_pembangunan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `detailKetSubDpaId` bigint DEFAULT NULL,
  `nilaiKontrak` int DEFAULT NULL,
  `nomorKontrak` text,
  `tanggalKontrak` date DEFAULT NULL,
  `pejabatPpk` text,
  `pelaksana` text,
  `lokasiRealisasiAnggaran` text,
  `jangkaWaktu` int DEFAULT NULL,
  `mulaiKerja` date DEFAULT NULL,
  `kendalaHambatan` longtext,
  `tenagaTerja` int DEFAULT NULL,
  `penerapanK3` enum('ya','tidak') DEFAULT NULL,
  `keterangan` longtext,
  `progressPelaksanaan` int DEFAULT NULL,
  `rencanaPelaksanaan` int DEFAULT NULL,
  `realisasiPelaksanaan` int DEFAULT NULL,
  `deviasiPelaksanaan` int DEFAULT NULL,
  `keselamatanKontruksi` longtext,
  `catatan` longtext,
  `timMonitoring` longtext,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dinas_4.rencana_pembangunan: ~0 rows (approximately)
INSERT INTO `rencana_pembangunan` (`id`, `detailKetSubDpaId`, `nilaiKontrak`, `nomorKontrak`, `tanggalKontrak`, `pejabatPpk`, `pelaksana`, `lokasiRealisasiAnggaran`, `jangkaWaktu`, `mulaiKerja`, `kendalaHambatan`, `tenagaTerja`, `penerapanK3`, `keterangan`, `progressPelaksanaan`, `rencanaPelaksanaan`, `realisasiPelaksanaan`, `deviasiPelaksanaan`, `keselamatanKontruksi`, `catatan`, `timMonitoring`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, 4000000, 'PEM-003-KO', '2023-06-06', 'Pak pak', 'Pak pak', 'asdasddas', 10, '2023-07-20', '-', 100, 'ya', '-', 20, 30, 40, 60, '1', '{\n    "KepalaTukangBerasal": {\n        "Asal": "asd",\n        "PunyaSKTatauSKK": "ya"\n    },\n    "JumlahPekerja": "4",\n    "PekerjaBerasalDariKabupatenPesisirBarat": "44",\n    "PekerjaBerasalDariLuarKabupatenPesisirBarat": "4",\n    "MaterialBerasalDari": {\n        "PesisirBarat": "4",\n        "LuarPesisirBarat": "4"\n    }\n}', '[\n    {\n        "NamaTimAnggaran": "asdf",\n        "NipTimAnggaran": "asdf",\n        "JabatanTimAnggaran": "asd"\n    },\n    {\n        "NamaTimAnggaran": "asdf",\n        "NipTimAnggaran": "asdf",\n        "JabatanTimAnggaran": "asd"\n    }\n]', '2023-09-02 10:07:38', '2023-09-02 16:27:28', NULL);

-- Dumping structure for table dinas_4.rencana_pengambilan
CREATE TABLE IF NOT EXISTS `rencana_pengambilan` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `subDpaId` bigint NOT NULL,
  `bulan` varchar(255) NOT NULL,
  `pengambilan` int NOT NULL,
  `realisasi` varchar(255) DEFAULT NULL,
  `jenisBelanja` varchar(255) NOT NULL,
  `totalAnggaranJenisBelanja` int NOT NULL,
  `statusPelaksana` longtext,
  `keteranganPelaksanaan` longtext,
  `persentase` varchar(255) DEFAULT NULL,
  `statusKemanfaatan` varchar(255) DEFAULT NULL,
  `keteranganPermasalahan` longtext,
  `dokumenBuktiPendukung` longtext,
  `fotoBuktiPendukung` longtext,
  `videoBuktiPendukung` longtext,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subDpaId` (`subDpaId`),
  CONSTRAINT `rencana_pengambilan_ibfk_1` FOREIGN KEY (`subDpaId`) REFERENCES `sub_dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dinas_4.rencana_pengambilan: ~2 rows (approximately)
INSERT INTO `rencana_pengambilan` (`id`, `subDpaId`, `bulan`, `pengambilan`, `realisasi`, `jenisBelanja`, `totalAnggaranJenisBelanja`, `statusPelaksana`, `keteranganPelaksanaan`, `persentase`, `statusKemanfaatan`, `keteranganPermasalahan`, `dokumenBuktiPendukung`, `fotoBuktiPendukung`, `videoBuktiPendukung`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, 'Januari', 100000, '25000', '1', 100000, '2', 'asdad', '25', '2', 'asd', 'asd', 'reasdquired', 'asd', '2023-09-01 20:59:54', '2023-09-01 22:04:22', NULL),
	(2, 1, 'Januari', 1000000, '150000', '2', 1000000, '2', 'asdad', '15', '2', 'asd', 'asd', 'reasdquired', 'asd', '2023-09-01 22:05:12', NULL, NULL);

-- Dumping structure for view dinas_4.rincian_objek_rekening
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `rincian_objek_rekening` (
	`id` BIGINT(19) NOT NULL,
	`objekRekeningId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`uraianAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`deskripsiAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.satuan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `satuan` (
	`id` BIGINT(19) NOT NULL,
	`satuan` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`pembangunan` INT(10) NOT NULL,
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for table dinas_4.sub_dpa
CREATE TABLE IF NOT EXISTS `sub_dpa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `dpaId` bigint NOT NULL,
  `subKegiatanId` bigint NOT NULL,
  `sumberDanaId` bigint NOT NULL,
  `lokasi` text NOT NULL,
  `target` int NOT NULL,
  `waktuPelaksanaan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `createAt` varchar(50) NOT NULL,
  `updateAt` varchar(50) DEFAULT NULL,
  `deleteAt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dpaId` (`dpaId`),
  CONSTRAINT `sub_dpa_ibfk_1` FOREIGN KEY (`dpaId`) REFERENCES `dpa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dinas_4.sub_dpa: ~0 rows (approximately)
INSERT INTO `sub_dpa` (`id`, `dpaId`, `subKegiatanId`, `sumberDanaId`, `lokasi`, `target`, `waktuPelaksanaan`, `keterangan`, `createAt`, `updateAt`, `deleteAt`) VALUES
	(1, 1, 1, 1, 'asd', 100, 'asd', 'asd', '2023-08-28 09:10:05', NULL, NULL),
	(2, 1, 1, 1, 'asd', 100, 'asd', 'asd', '2023-08-31 09:21:41', NULL, NULL);

-- Dumping structure for view dinas_4.sub_kegiatan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `sub_kegiatan` (
	`id` BIGINT(19) NOT NULL,
	`kegiatanId` BIGINT(19) NOT NULL,
	`satuanId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`nomenklatur` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`kinerja` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`indikator` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.sub_rincian_objek_rekening
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `sub_rincian_objek_rekening` (
	`id` BIGINT(19) NOT NULL,
	`rincianObjekRekeningId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`uraianAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`deskripsiAkun` TEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.sumber_dana
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `sumber_dana` (
	`id` BIGINT(19) NOT NULL,
	`sumberDana` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.tahun
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `tahun` (
	`id` BIGINT(19) NOT NULL,
	`tahun` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`active` INT(10) NOT NULL,
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.unit
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `unit` (
	`id` BIGINT(19) NOT NULL,
	`organisasiId` BIGINT(19) NOT NULL,
	`kode` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`nomenklatur` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.urusan
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `urusan` (
	`id` BIGINT(19) NOT NULL,
	`kode` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`nomenklatur` LONGTEXT NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.wilayah
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `wilayah` (
	`id` BIGINT(19) NOT NULL,
	`wilayah` VARCHAR(255) NOT NULL COLLATE 'utf8mb3_general_ci',
	`createAt` VARCHAR(50) NOT NULL COLLATE 'utf8mb3_general_ci',
	`updateAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci',
	`deleteAt` VARCHAR(50) NULL COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view dinas_4.akun_rekening
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `akun_rekening`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`akun_rekening` AS select `akun_rekening`.`id` AS `id`,`akun_rekening`.`kode` AS `kode`,`akun_rekening`.`uraianAkun` AS `uraianAkun`,`akun_rekening`.`deskripsiAkun` AS `deskripsiAkun`,`akun_rekening`.`createAt` AS `createAt`,`akun_rekening`.`updateAt` AS `updateAt`,`akun_rekening`.`deleteAt` AS `deleteAt` from `akun_rekening`;

-- Dumping structure for view dinas_4.bidang
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `bidang`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`bidang` AS select `bidang`.`id` AS `id`,`bidang`.`urusanId` AS `urusanId`,`bidang`.`kode` AS `kode`,`bidang`.`nomenklatur` AS `nomenklatur`,`bidang`.`createAt` AS `createAt`,`bidang`.`updateAt` AS `updateAt`,`bidang`.`deleteAt` AS `deleteAt` from `bidang`;

-- Dumping structure for view dinas_4.jenis_rekening
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `jenis_rekening`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`jenis_rekening` AS select `jenis_rekening`.`id` AS `id`,`jenis_rekening`.`kelompokRekeningId` AS `kelompokRekeningId`,`jenis_rekening`.`kode` AS `kode`,`jenis_rekening`.`uraianAkun` AS `uraianAkun`,`jenis_rekening`.`deskripsiAkun` AS `deskripsiAkun`,`jenis_rekening`.`createAt` AS `createAt`,`jenis_rekening`.`updateAt` AS `updateAt`,`jenis_rekening`.`deleteAt` AS `deleteAt` from `jenis_rekening`;

-- Dumping structure for view dinas_4.kegiatan
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `kegiatan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`kegiatan` AS select `kegiatan`.`id` AS `id`,`kegiatan`.`programId` AS `programId`,`kegiatan`.`kode` AS `kode`,`kegiatan`.`nomenklatur` AS `nomenklatur`,`kegiatan`.`createAt` AS `createAt`,`kegiatan`.`updateAt` AS `updateAt`,`kegiatan`.`deleteAt` AS `deleteAt` from `kegiatan`;

-- Dumping structure for view dinas_4.kelompok_rekening
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `kelompok_rekening`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`kelompok_rekening` AS select `kelompok_rekening`.`id` AS `id`,`kelompok_rekening`.`akunRekeningId` AS `akunRekeningId`,`kelompok_rekening`.`kode` AS `kode`,`kelompok_rekening`.`uraianAkun` AS `uraianAkun`,`kelompok_rekening`.`deskripsiAkun` AS `deskripsiAkun`,`kelompok_rekening`.`createAt` AS `createAt`,`kelompok_rekening`.`updateAt` AS `updateAt`,`kelompok_rekening`.`deleteAt` AS `deleteAt` from `kelompok_rekening`;

-- Dumping structure for view dinas_4.komponen_pembangunan
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `komponen_pembangunan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`komponen_pembangunan` AS select `komponen_pembangunan`.`id` AS `id`,`komponen_pembangunan`.`parentId` AS `parentId`,`komponen_pembangunan`.`komponen` AS `komponen`,`komponen_pembangunan`.`createAt` AS `createAt`,`komponen_pembangunan`.`updateAt` AS `updateAt`,`komponen_pembangunan`.`deleteAt` AS `deleteAt` from `komponen_pembangunan`;

-- Dumping structure for view dinas_4.objek_rekening
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `objek_rekening`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`objek_rekening` AS select `objek_rekening`.`id` AS `id`,`objek_rekening`.`jenisRekeningId` AS `jenisRekeningId`,`objek_rekening`.`kode` AS `kode`,`objek_rekening`.`uraianAkun` AS `uraianAkun`,`objek_rekening`.`deskripsiAkun` AS `deskripsiAkun`,`objek_rekening`.`createAt` AS `createAt`,`objek_rekening`.`updateAt` AS `updateAt`,`objek_rekening`.`deleteAt` AS `deleteAt` from `objek_rekening`;

-- Dumping structure for view dinas_4.organisasi
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `organisasi`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`organisasi` AS select `organisasi`.`id` AS `id`,`organisasi`.`bidangId` AS `bidangId`,`organisasi`.`kode` AS `kode`,`organisasi`.`nomenklatur` AS `nomenklatur`,`organisasi`.`createAt` AS `createAt`,`organisasi`.`updateAt` AS `updateAt`,`organisasi`.`deleteAt` AS `deleteAt` from `organisasi`;

-- Dumping structure for view dinas_4.program
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `program`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`program` AS select `program`.`id` AS `id`,`program`.`bidangId` AS `bidangId`,`program`.`kode` AS `kode`,`program`.`nomenklatur` AS `nomenklatur`,`program`.`createAt` AS `createAt`,`program`.`updateAt` AS `updateAt`,`program`.`deleteAt` AS `deleteAt` from `program`;

-- Dumping structure for view dinas_4.rincian_objek_rekening
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `rincian_objek_rekening`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`rincian_objek_rekening` AS select `rincian_objek_rekening`.`id` AS `id`,`rincian_objek_rekening`.`objekRekeningId` AS `objekRekeningId`,`rincian_objek_rekening`.`kode` AS `kode`,`rincian_objek_rekening`.`uraianAkun` AS `uraianAkun`,`rincian_objek_rekening`.`deskripsiAkun` AS `deskripsiAkun`,`rincian_objek_rekening`.`createAt` AS `createAt`,`rincian_objek_rekening`.`updateAt` AS `updateAt`,`rincian_objek_rekening`.`deleteAt` AS `deleteAt` from `rincian_objek_rekening`;

-- Dumping structure for view dinas_4.satuan
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `satuan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`satuan` AS select `satuan`.`id` AS `id`,`satuan`.`satuan` AS `satuan`,`satuan`.`pembangunan` AS `pembangunan`,`satuan`.`createAt` AS `createAt`,`satuan`.`updateAt` AS `updateAt`,`satuan`.`deleteAt` AS `deleteAt` from `satuan`;

-- Dumping structure for view dinas_4.sub_kegiatan
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `sub_kegiatan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`sub_kegiatan` AS select `sub_kegiatan`.`id` AS `id`,`sub_kegiatan`.`kegiatanId` AS `kegiatanId`,`sub_kegiatan`.`satuanId` AS `satuanId`,`sub_kegiatan`.`kode` AS `kode`,`sub_kegiatan`.`nomenklatur` AS `nomenklatur`,`sub_kegiatan`.`kinerja` AS `kinerja`,`sub_kegiatan`.`indikator` AS `indikator`,`sub_kegiatan`.`createAt` AS `createAt`,`sub_kegiatan`.`updateAt` AS `updateAt`,`sub_kegiatan`.`deleteAt` AS `deleteAt` from `sub_kegiatan`;

-- Dumping structure for view dinas_4.sub_rincian_objek_rekening
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `sub_rincian_objek_rekening`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`sub_rincian_objek_rekening` AS select `sub_rincian_objek_rekening`.`id` AS `id`,`sub_rincian_objek_rekening`.`rincianObjekRekeningId` AS `rincianObjekRekeningId`,`sub_rincian_objek_rekening`.`kode` AS `kode`,`sub_rincian_objek_rekening`.`uraianAkun` AS `uraianAkun`,`sub_rincian_objek_rekening`.`deskripsiAkun` AS `deskripsiAkun`,`sub_rincian_objek_rekening`.`createAt` AS `createAt`,`sub_rincian_objek_rekening`.`updateAt` AS `updateAt`,`sub_rincian_objek_rekening`.`deleteAt` AS `deleteAt` from `sub_rincian_objek_rekening`;

-- Dumping structure for view dinas_4.sumber_dana
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `sumber_dana`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`sumber_dana` AS select `sumber_dana`.`id` AS `id`,`sumber_dana`.`sumberDana` AS `sumberDana`,`sumber_dana`.`createAt` AS `createAt`,`sumber_dana`.`updateAt` AS `updateAt`,`sumber_dana`.`deleteAt` AS `deleteAt` from `sumber_dana`;

-- Dumping structure for view dinas_4.tahun
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `tahun`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`tahun` AS select `tahun`.`id` AS `id`,`tahun`.`tahun` AS `tahun`,`tahun`.`active` AS `active`,`tahun`.`createAt` AS `createAt`,`tahun`.`updateAt` AS `updateAt`,`tahun`.`deleteAt` AS `deleteAt` from `tahun`;

-- Dumping structure for view dinas_4.unit
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `unit`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`unit` AS select `unit`.`id` AS `id`,`unit`.`organisasiId` AS `organisasiId`,`unit`.`kode` AS `kode`,`unit`.`nomenklatur` AS `nomenklatur`,`unit`.`createAt` AS `createAt`,`unit`.`updateAt` AS `updateAt`,`unit`.`deleteAt` AS `deleteAt` from `unit`;

-- Dumping structure for view dinas_4.urusan
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `urusan`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`urusan` AS select `urusan`.`id` AS `id`,`urusan`.`kode` AS `kode`,`urusan`.`nomenklatur` AS `nomenklatur`,`urusan`.`createAt` AS `createAt`,`urusan`.`updateAt` AS `updateAt`,`urusan`.`deleteAt` AS `deleteAt` from `urusan`;

-- Dumping structure for view dinas_4.wilayah
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `wilayah`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `dinas_4`.`wilayah` AS select `wilayah`.`id` AS `id`,`wilayah`.`wilayah` AS `wilayah`,`wilayah`.`createAt` AS `createAt`,`wilayah`.`updateAt` AS `updateAt`,`wilayah`.`deleteAt` AS `deleteAt` from `wilayah`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
