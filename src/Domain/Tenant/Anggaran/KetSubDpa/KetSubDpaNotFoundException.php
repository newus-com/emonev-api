<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\KetSubDpa;

use App\Domain\DomainException\DomainRecordNotFoundException;

class KetSubDpaNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Rincian rekening tidak ditemukan';
}
