<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\Dpa;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DpaIsExistException extends DomainRecordNotFoundException
{
    public $message = 'DPA sudah ada sebelumnya';
}
