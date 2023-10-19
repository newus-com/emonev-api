<?php

declare(strict_types=1);

namespace App\Domain\Dinas;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class DinasFailedUpdateException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal mengubah data dinas';
}
