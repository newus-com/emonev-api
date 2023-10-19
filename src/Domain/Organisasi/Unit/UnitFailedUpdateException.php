<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Unit;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class UnitFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah unit';
}
