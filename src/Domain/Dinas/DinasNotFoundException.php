<?php

declare(strict_types=1);

namespace App\Domain\Dinas;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DinasNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Dinas tidak ditemukan';
}
