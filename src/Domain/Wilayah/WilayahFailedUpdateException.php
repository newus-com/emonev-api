<?php

declare(strict_types=1);

namespace App\Domain\Wilayah;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class WilayahFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah wilayah';
}
