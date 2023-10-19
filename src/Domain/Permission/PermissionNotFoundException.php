<?php

declare(strict_types=1);

namespace App\Domain\Permission;

use App\Domain\DomainException\DomainRecordNotFoundException;

class PermissionNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Permission tidak ditemukan';
}
