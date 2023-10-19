<?php

declare(strict_types=1);

namespace App\Domain\Rekening\RincianObjekRekening;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class RincianObjekRekeningFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah rincian objek rekening';
}
