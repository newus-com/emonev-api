<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Bidang;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class BidangFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah bidang';
}
