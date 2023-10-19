<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Unit;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class UnitFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan unit';
}
