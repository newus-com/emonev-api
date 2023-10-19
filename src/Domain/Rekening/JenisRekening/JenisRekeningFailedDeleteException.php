<?php

declare(strict_types=1);

namespace App\Domain\Rekening\JenisRekening;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class JenisRekeningFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus jenis rekening';
}
