<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Pembangunan\Perencanaan;

use Composite\Entity\AbstractEntity;

class Perencanaan extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $detailKetSubDpaId
     * @param int|null $nilaiKontrak
     * @param string|null $nomorKontrak
     * @param string|null $tanggalKontrak
     * @param string|null $pejabatPpk
     * @param string|null $pelaksana
     * @param string|null $lokasiRealisasiAnggaran
     * @param int|null $jangkaWaktu
     * @param string|null $mulaiKerja
     * @param string|null $kendalaHambatan
     * @param string|null $keterangan
     * @param int|null $tenagaTerja
     * @param string|null $penerapanK3
     * @param int|null $progressPelaksanaan
     * @param int|null $rencanaPelaksanaan
     * @param int|null $realisasiPelaksanaan
     * @param int|null $deviasiPelaksanaan
     * @param int|null $keselamatanKontruksi
     * @param string|null $catatan
     * @param string|null $timMonitoring
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public int|null $detailKetSubDpaId = null,
        public int|null $nilaiKontrak = null,
        public string|null $nomorKontrak = null,
        public string|null $tanggalKontrak = null,
        public string|null $pejabatPpk = null,
        public string|null $pelaksana = null,
        public string|null $lokasiRealisasiAnggaran = null,
        public int|null $jangkaWaktu = null,
        public string|null $mulaiKerja = null,
        public string|null $kendalaHambatan = null,
        public int|null $tenagaTerja = null,
        public string|null $penerapanK3 = null,
        public string|null $keterangan = null,
        public int|null $progressPelaksanaan = null,
        public int|null $rencanaPelaksanaan = null,
        public int|null $realisasiPelaksanaan = null,
        public int|null $deviasiPelaksanaan = null,
        public int|null $keselamatanKontruksi = null,
        public string|null $catatan = null,
        public string|null $timMonitoring = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
