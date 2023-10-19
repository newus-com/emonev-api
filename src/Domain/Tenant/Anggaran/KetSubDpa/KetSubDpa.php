<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\KetSubDpa;

use Composite\Entity\AbstractEntity;

class KetSubDpa extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $subDpaId,
     * @param int|null $subRincianObjekRekeningId,
     * @param int|null $satuanId
     * @param string|null $uraian
     * @param string|null $spesifikasi
     * @param string|null $koefisien
     * @param string|null $harga
     * @param string|null $ppn
     * @param string|null $jumlah
     * @param int|null $jumlahAnggaran,
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public int|null $subDpaId = null,
        public int|null $subRincianObjekRekeningId = null,
        public int|null $satuanId = null,
        public string|null $uraian = null,
        public string|null $spesifikasi = null,
        public int|null $koefisien = null,
        public int|null $harga = null,
        public int|null $ppn = null,
        public string|null $jumlah = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}