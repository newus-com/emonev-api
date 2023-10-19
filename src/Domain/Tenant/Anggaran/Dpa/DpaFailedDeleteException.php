<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\Dpa;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class DpaFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus DPA';
}
