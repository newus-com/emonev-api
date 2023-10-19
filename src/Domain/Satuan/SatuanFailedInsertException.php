<?php

declare(strict_types=1);

namespace App\Domain\Satuan;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class SatuanFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan satuan';
}
