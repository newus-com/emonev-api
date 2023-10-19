<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Kegiatan;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class KegiatanFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan kegiatan';
}
