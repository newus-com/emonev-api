<?php

declare(strict_types=1);

namespace App\Domain\Dinas;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class DinasFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambah data dinas';
}
