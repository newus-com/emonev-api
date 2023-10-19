<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\KetSubDpa;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class KetSubDpaFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah rincian rekening';
}
