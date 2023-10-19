<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\DetailKetSubDpa;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class DetailKetSubDpaFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan rincian belanja';
}
