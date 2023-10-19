<?php

declare(strict_types=1);

namespace App\Domain\Rekening\AkunRekening;

use App\Domain\DomainException\DomainRecordFailedInsertException;

class AkunRekeningFailedInsertException extends DomainRecordFailedInsertException
{
    public $message = 'Gagal menambahkan akun rekening';
}
