<?php

declare(strict_types=1);

namespace App\Domain\Dinas;

use JsonSerializable;

use App\Domain\Role\Role;
use App\Domain\User\User;
use Composite\Entity\AbstractEntity;

class Dinas extends AbstractEntity
{
    /**
     * Summary of __construct
     * @param int|null $id
     * @param string|null $name
     * @param string|null $email
     * @param string|null $noHp
     * @param string|null $address
     * @param string|null $logo
     * @var ?Role|null|array $role
     * @var ?User|null|array $user
     * @param string|null $createAt
     * @param string|null $updateAt
     * @param string|null $deleteAt
     */
    public function __construct(
        public int|null $id = null,
        public string|null $name = null,
        public string|null $email = null,
        public string|null $noHp = null,
        public string|null $address = null,
        public string|null $logo = null,
        public ?array $role = null,
        public ?array $user = null,
        public string|null $createAt = null,
        public string|null $updateAt = null,
        public string|null $deleteAt = null,
    ) {
    }
}
