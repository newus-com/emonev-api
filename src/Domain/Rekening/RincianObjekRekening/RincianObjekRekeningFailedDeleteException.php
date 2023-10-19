<?php

declare(strict_types=1);

namespace App\Domain\Rekening\RincianObjekRekening;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class RincianObjekRekeningFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus rincian objek rekening';
}
