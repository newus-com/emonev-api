<?php

declare(strict_types=1);

namespace App\Domain\Satuan;

use Composite\Entity\AbstractEntity;

class Satuan extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $satuan
     * @param string|null $pembangunan
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $satuan = null,
        public int|null $pembangunan = 0,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
