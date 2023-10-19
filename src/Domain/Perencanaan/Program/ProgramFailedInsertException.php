<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Program;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class ProgramFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan program';
}
