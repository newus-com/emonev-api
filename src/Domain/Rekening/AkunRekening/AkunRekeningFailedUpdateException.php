<?php

declare(strict_types=1);

namespace App\Domain\Rekening\AkunRekening;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class AkunRekeningFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah akun rekening';
}
