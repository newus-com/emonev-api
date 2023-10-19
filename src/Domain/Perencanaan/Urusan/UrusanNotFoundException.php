<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Urusan;

use App\Domain\DomainException\DomainRecordNotFoundException;

class UrusanNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Urusan tidak ditemukan';
}
