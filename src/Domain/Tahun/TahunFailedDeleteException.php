<?php

declare(strict_types=1);

namespace App\Domain\Tahun;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class TahunFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus tahun';
}
