<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Organisasi;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class OrganisasiFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus organisasi';
}
