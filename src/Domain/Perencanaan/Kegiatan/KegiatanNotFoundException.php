<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Kegiatan;

use App\Domain\DomainException\DomainRecordNotFoundException;

class KegiatanNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Kegiatan tidak ditemukan';
}
