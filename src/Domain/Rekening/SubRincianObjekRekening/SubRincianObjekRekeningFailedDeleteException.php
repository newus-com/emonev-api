<?php

declare(strict_types=1);

namespace App\Domain\Rekening\SubRincianObjekRekening;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class SubRincianObjekRekeningFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus sub rincian objek rekening';
}
