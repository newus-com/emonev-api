<?php

declare(strict_types=1);

namespace App\Domain\SumberDana;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class SumberDanaFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan sumber dana';
}
