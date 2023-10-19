<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Urusan;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class UrusanFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah urusan';
}
