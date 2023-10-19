<?php

declare(strict_types=1);

namespace App\Domain\Rekening\ObjekRekening;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class ObjekRekeningFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus objek rekening';
}
