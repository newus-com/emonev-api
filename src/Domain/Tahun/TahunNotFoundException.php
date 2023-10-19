<?php

declare(strict_types=1);

namespace App\Domain\Tahun;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TahunNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Tahun tidak ditemukan';
}
