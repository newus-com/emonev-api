<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\DetailKetSubDpa;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DetailKetSubDpaNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Rincian belanja tidak ditemukan';
}
