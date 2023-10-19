<?php

declare(strict_types=1);

namespace App\Domain\Rekening\KelompokRekening;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class KelompokRekeningFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus kelompok rekening';
}
