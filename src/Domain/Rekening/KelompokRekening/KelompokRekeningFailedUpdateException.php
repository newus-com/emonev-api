<?php

declare(strict_types=1);

namespace App\Domain\Rekening\KelompokRekening;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class KelompokRekeningFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah kelompok rekening';
}
