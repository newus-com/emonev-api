<?php

declare(strict_types=1);

namespace App\Domain\Tahun;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class TahunFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah tahun';
}
