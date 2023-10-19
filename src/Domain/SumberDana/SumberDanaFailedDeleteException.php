<?php

declare(strict_types=1);

namespace App\Domain\SumberDana;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class SumberDanaFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus sumber dana';
}
