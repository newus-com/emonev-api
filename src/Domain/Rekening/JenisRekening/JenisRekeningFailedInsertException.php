<?php

declare(strict_types=1);

namespace App\Domain\Rekening\JenisRekening;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class JenisRekeningFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan jenis rekening';
}
