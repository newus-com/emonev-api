<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\Dpa;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class DpaFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan Informasi DPA';
}
