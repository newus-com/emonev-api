<?php

declare(strict_types=1);

namespace App\Domain\Rekening\RincianObjekRekening;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class RincianObjekRekeningFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan rincian objek rekening';
}
