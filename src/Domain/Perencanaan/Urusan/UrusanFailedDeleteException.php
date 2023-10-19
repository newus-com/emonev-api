<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Urusan;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class UrusanFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus urusan';
}
