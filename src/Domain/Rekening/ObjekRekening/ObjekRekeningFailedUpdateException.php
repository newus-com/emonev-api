<?php

declare(strict_types=1);

namespace App\Domain\Rekening\ObjekRekening;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class ObjekRekeningFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah objek rekening';
}
