<?php

declare(strict_types=1);

namespace App\Domain\Dinas;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class DinasFailedDeleteException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menghapus data dinas';
}
