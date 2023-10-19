<?php

declare(strict_types=1);

namespace App\Domain\SumberDana;

use Composite\Entity\AbstractEntity;

class SumberDana extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $sumberDana
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $sumberDana = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
