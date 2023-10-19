<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\SubDpa;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SubDpaNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Sub Kegiatan tidak ditemukan';
}
