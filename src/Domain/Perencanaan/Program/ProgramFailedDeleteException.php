<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Program;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class ProgramFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus program';
}
