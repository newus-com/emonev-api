<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Organisasi;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class OrganisasiFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan organisasi';
}
