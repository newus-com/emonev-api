<?php

declare(strict_types=1);

namespace App\Domain\Wilayah;

use Composite\Entity\AbstractEntity;

class Wilayah extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $wilayah
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $wilayah = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
