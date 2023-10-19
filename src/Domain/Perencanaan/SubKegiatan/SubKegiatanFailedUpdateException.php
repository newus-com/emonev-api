<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\SubKegiatan;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class SubKegiatanFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah sub kegiatan';
}
