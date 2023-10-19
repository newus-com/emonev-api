<?php

declare(strict_types=1);

namespace App\Domain\Tahun;

use Composite\Entity\AbstractEntity;

class Tahun extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $tahun
     * @param int|null $active
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $tahun = null,
        public int|null $active = 0,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
