<?php

declare(strict_types=1);

namespace App\Domain\Role;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class PermissionBadRequestException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal Menambahkan Permission';
}
