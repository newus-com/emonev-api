<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\SubKegiatan;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SubKegiatanNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Sub kegiatan tidak ditemukan';
}
