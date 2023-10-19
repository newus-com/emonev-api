<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\Dpa;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class DpaFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah Informasi DPA';
}
