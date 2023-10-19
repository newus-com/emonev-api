<?php

declare(strict_types=1);

namespace App\Domain\Role;

use App\Domain\Dinas\Dinas;
use App\Domain\Module\Module;
use App\Domain\Permission\Permission;
use Composite\Entity\AbstractEntity;

class Role extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $role
     * @param int|null $dinasId
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     * @var ?Dinas|array|null $dinas
     * @var ?Module|null $module
     * @var ?Permission|array|null $permission
     */
    public function __construct(
        public int|null $id = null,
        public int|null $dinasId = null,
        public string|null $role = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
        public array|null $dinas = null,
        public array|null $module = null,
        public array|null $permission = null,
    ) {
    }
}
