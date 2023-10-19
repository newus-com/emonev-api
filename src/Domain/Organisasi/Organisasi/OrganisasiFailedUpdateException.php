<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Organisasi;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class OrganisasiFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah organisasi';
}
