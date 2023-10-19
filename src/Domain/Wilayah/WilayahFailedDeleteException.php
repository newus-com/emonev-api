<?php

declare(strict_types=1);

namespace App\Domain\Wilayah;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class WilayahFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus wilayah';
}
