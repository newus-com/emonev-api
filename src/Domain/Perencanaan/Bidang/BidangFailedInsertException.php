<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Bidang;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class BidangFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan bidang';
}
