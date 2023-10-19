<?php

declare(strict_types=1);

namespace App\Domain\Rekening\SubRincianObjekRekening;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class SubRincianObjekRekeningFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan sub rincian objek rekening';
}
