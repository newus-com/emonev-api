<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Urusan;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class UrusanFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan urusan';
}
