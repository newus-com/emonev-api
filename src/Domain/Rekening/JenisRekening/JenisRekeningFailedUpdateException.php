<?php

declare(strict_types=1);

namespace App\Domain\Rekening\JenisRekening;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class JenisRekeningFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah jenis rekening';
}
