<?php

declare(strict_types=1);

namespace App\Domain\Rekening\SubRincianObjekRekening;

use App\Domain\DomainException\DomainRecordFailedUpdateException;

class SubRincianObjekRekeningFailedUpdateException extends DomainRecordFailedUpdateException
{
    public $message = 'Gagal mengubah sub rincian objek rekening';
}
