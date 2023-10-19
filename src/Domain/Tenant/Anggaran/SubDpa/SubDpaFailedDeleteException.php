<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\SubDpa;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class SubDpaFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus Sub Kegiatan';
}
