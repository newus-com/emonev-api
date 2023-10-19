<?php

declare(strict_types=1);

namespace App\Domain\Wilayah;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class WilayahFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan wilayah';
}
