<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Kegiatan;

use Composite\Entity\AbstractEntity;

class Kegiatan extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $programId
     * @param string|null $kode
     * @param string|null $nomenklatur
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public int|null $programId = null,
        public string|null $kode = null,
        public string|null $nomenklatur = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
