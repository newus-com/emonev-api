<?php

declare(strict_types=1);

namespace App\Domain\SumberDana;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SumberDanaNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Sumber Dana tidak ditemukan';
}
