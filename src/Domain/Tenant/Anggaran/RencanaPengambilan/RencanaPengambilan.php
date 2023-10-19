<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\RencanaPengambilan;

use Composite\Entity\AbstractEntity;

class RencanaPengambilan extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $subDpaId
     * @param string|null $bulan
     * @param int|null $pengambilan
     * @param string|null $realisasi
     * @param string|null $jenisBelanja
     * @param int|null $totalAnggaranJenisBelanja
     * @param string|null $statusPelaksana
     * @param string|null $keteranganPelaksanaan
     * @param string|null $persentase
     * @param string|null $statusKemanfaatan
     * @param string|null $keteranganPermasalahan
     * @param string|null $dokumenBuktiPendukung
     * @param string|null $fotoBuktiPendukung
     * @param string|null $videoBuktiPendukung
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $subDpaId = null,
        public string|null $bulan = null,
        public int|null $pengambilan = null,
        public string|null $realisasi = null,
        public string|null $jenisBelanja = null,
        public int|null $totalAnggaranJenisBelanja = null,
        public string|null $statusPelaksana = null,
        public string|null $keteranganPelaksanaan = null,
        public string|null $persentase = null,
        public string|null $statusKemanfaatan = null,
        public string|null $keteranganPermasalahan = null,
        public string|null $dokumenBuktiPendukung = null,
        public string|null $fotoBuktiPendukung = null,
        public string|null $videoBuktiPendukung = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}