<?php

declare(strict_types=1);

namespace App\Domain\Module;

use App\Domain\Permission\Permission;
use Composite\Entity\AbstractEntity;
use JsonSerializable;

class Module extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $name
     * @var Permission|array $permission
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $name = null,
        public array|null $permission = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
