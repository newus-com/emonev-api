<?php

declare(strict_types=1);

namespace App\Domain\Satuan;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class SatuanFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah satuan';
}
