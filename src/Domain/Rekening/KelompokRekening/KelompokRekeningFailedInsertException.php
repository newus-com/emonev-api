<?php

declare(strict_types=1);

namespace App\Domain\Rekening\KelompokRekening;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class KelompokRekeningFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan kelompok rekening';
}
