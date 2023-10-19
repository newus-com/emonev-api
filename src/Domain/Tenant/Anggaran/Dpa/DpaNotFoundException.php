<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\Dpa;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DpaNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'DPA tidak ditemukan';
}
