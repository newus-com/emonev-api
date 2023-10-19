<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\SubDpa;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class SubDpaFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan Sub Kegiatan';
}
