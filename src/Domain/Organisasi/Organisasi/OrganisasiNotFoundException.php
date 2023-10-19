<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Organisasi;

use App\Domain\DomainException\DomainRecordNotFoundException;

class OrganisasiNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Organisasi tidak ditemukan';
}
