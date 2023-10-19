<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\DetailKetSubDpa;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class DetailKetSubDpaFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus rincian belanja';
}
