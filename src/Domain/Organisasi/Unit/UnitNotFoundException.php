<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Unit;

use App\Domain\DomainException\DomainRecordNotFoundException;

class UnitNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Unit tidak ditemukan';
}
