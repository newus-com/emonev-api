<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Program;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ProgramNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Program tidak ditemukan';
}
