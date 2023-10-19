<?php

declare(strict_types=1);

namespace App\Domain\Role;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class RoleBadRequestException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal Membuat Role';
}
