<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\SubKegiatan;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class SubKegiatanFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus sub kegiatan';
}
