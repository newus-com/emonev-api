<?php

declare(strict_types=1);

namespace App\Domain\KomponenPembangunan;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class KomponenPembangunanFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan komponen pembangunan';
}
