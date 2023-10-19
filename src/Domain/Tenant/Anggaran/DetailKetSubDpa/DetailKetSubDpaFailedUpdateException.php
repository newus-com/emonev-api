<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\DetailKetSubDpa;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class DetailKetSubDpaFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah rincian belanja';
}
