<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class UserBadRequestException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal Membuat User';
}
