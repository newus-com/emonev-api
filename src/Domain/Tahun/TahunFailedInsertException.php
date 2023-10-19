<?php

declare(strict_types=1);

namespace App\Domain\Tahun;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class TahunFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan tahun';
}
