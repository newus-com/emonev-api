<?php

declare(strict_types=1);

namespace App\Domain\Module;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ModuleNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Module tidak ditemukan';
}
