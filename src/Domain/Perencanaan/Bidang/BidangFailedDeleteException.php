<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Bidang;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class BidangFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus bidang';
}
