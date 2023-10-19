<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\SubKegiatan;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class SubKegiatanFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan sub kegiatan';
}
