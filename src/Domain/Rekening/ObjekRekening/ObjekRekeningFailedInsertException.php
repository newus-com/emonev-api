<?php

declare(strict_types=1);

namespace App\Domain\Rekening\ObjekRekening;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class ObjekRekeningFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan objek rekening';
}
