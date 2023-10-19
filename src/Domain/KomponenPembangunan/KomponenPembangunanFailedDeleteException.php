<?php

declare(strict_types=1);

namespace App\Domain\KomponenPembangunan;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class KomponenPembangunanFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus komponen pembangunan';
}
