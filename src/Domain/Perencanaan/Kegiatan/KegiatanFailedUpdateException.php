<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Kegiatan;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class KegiatanFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah kegiatan';
}
