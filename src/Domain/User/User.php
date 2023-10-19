<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Dinas\Dinas;
use App\Domain\Permission\Permission;
use Composite\Entity\AbstractEntity;

class User extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     * @param int|null $status
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     * @var ?Dinas[]|array|null $dinas
     * @var ?Permission[]|array|null $specialPermission
     * @var ?[]|array|null $globalRole
     */
    public function __construct(
        public int|null $id = null,
        public string|null $name = null,
        public string|null $email = null,
        public string|null $password = null,
        public int|null $status = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
        public array|null $dinas = null,
        public array|null $specialPermission = null,
        public array|null $globalRole = null,
    ) {
    }
}
