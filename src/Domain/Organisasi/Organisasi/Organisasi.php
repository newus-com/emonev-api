<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Organisasi;

use Composite\Entity\AbstractEntity;

class Organisasi extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $bidangId
     * @param string|null $kode
     * @param string|null $nomenklatur
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public int|null $bidangId = null,
        public string|null $kode = null,
        public string|null $nomenklatur = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
