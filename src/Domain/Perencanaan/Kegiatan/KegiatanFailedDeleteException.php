<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Kegiatan;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class KegiatanFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus kegiatan';
}
