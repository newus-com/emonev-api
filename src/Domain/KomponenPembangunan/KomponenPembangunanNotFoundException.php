<?php

declare(strict_types=1);

namespace App\Domain\KomponenPembangunan;

use App\Domain\DomainException\DomainRecordNotFoundException;

class KomponenPembangunanNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Komponen pembangunan tidak ditemukan';
}
