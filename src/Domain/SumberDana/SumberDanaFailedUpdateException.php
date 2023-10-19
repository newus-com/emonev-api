<?php

declare(strict_types=1);

namespace App\Domain\SumberDana;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class SumberDanaFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah sumber dana';
}
