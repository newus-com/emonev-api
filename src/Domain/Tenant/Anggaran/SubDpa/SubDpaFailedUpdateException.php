<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\SubDpa;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class SubDpaFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah Sub Kegiatan';
}
