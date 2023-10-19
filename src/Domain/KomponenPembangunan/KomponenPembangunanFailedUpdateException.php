<?php

declare(strict_types=1);

namespace App\Domain\KomponenPembangunan;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class KomponenPembangunanFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah komponen pembangunan';
}
