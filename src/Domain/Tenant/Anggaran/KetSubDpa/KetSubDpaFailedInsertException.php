<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\KetSubDpa;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class KetSubDpaFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan rincian rekening';
}
