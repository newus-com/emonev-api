<?php

declare(strict_types=1);

namespace App\Domain\Permission;

use App\Domain\Module\Module;
use Composite\Entity\AbstractEntity;

class Permission extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $name
     * @param string|null $description
     * @var Module|null $module
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $name = null,
        public string|null $description = null,
        public ?Module $module = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
