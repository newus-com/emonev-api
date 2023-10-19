<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\SubKegiatan;

use Composite\Entity\AbstractEntity;

class SubKegiatan extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $satuanId
     * @param int|null $kegiatanId
     * @param string|null $kode
     * @param string|null $nomenklatur
     * @param string|null $kinerja
     * @param string|null $indikator
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public int|null $satuanId = null,
        public int|null $kegiatanId = null,
        public string|null $kode = null,
        public string|null $nomenklatur = null,
        public string|null $kinerja = null,
        public string|null $indikator = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
