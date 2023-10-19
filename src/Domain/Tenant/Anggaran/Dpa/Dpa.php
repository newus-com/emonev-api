<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\Dpa;

use Composite\Entity\AbstractEntity;

class Dpa extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $noDpa
     * @param int|null $tahunId
     * @param int|null $dinasId
     * @param int|null $kegiatanId
     * @param int|null $unitId
     * @param string|null $jumlahAlokasi
     * @param string|null $penggunaAnggaran
     * @param string|null $ttdDpa
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $noDpa = null,
        public int|null $tahunId = null,
        public int|null $dinasId = null,
        public int|null $kegiatanId = null,
        public int|null $unitId = null,
        public string|null $jumlahAlokasi = null,
        public string|null $penggunaAnggaran = null,
        public string|null $ttdDpa = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}