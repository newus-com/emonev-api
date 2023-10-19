<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Program;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class ProgramFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah program';
}
