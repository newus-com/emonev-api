<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Bidang;

use App\Domain\DomainException\DomainRecordNotFoundException;

class BidangNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Bidang tidak ditemukan';
}
