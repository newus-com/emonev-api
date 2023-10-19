<?php

declare(strict_types=1);

namespace App\Domain\Rekening\AkunRekening;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class AkunRekeningFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus akun rekening';
}
