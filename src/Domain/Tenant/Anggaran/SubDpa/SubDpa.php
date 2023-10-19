<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\SubDpa;

use Composite\Entity\AbstractEntity;

class SubDpa extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $dpaId,
     * @param int|null $subKegiatanId,
     * @param int|null $sumberDanaId,
     * @param string|null $lokasi,
     * @param string|null $target,
     * @param string|null $waktuPelaksanaan,
     * @param string|null $keterangan,
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public int|null $dpaId = null,
        public int|null $subKegiatanId = null,
        public int|null $sumberDanaId = null,
        public string|null $lokasi = null,
        public string|null $target = null,
        public string|null $waktuPelaksanaan = null,
        public string|null $keterangan = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}