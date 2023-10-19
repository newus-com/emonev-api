<?php

declare(strict_types=1);

namespace App\Domain\Role;

use App\Domain\DomainException\DomainRecordNotFoundException;

class RoleNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Role tidak ditemukan';
}
