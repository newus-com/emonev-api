<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\KetSubDpa;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class KetSubDpaFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus rincian rekening';
}
