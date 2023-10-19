<?php

declare(strict_types=1);

namespace App\Domain\Satuan;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SatuanNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Satuan tidak ditemukan';
}
