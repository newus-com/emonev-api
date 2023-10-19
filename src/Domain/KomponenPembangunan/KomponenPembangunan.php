<?php

declare(strict_types=1);

namespace App\Domain\KomponenPembangunan;

use Composite\Entity\AbstractEntity;

class KomponenPembangunan extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param int|null $parentId
     * @param string|null $komponen
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public int|null $parentId = null,
        public string|null $komponen = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
