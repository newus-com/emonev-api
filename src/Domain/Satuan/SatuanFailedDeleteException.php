<?php

declare(strict_types=1);

namespace App\Domain\Satuan;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class SatuanFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus satuan';
}
