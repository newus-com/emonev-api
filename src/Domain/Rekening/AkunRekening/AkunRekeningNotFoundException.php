<?php

declare(strict_types=1);

namespace App\Domain\Rekening\AkunRekening;

use App\Domain\DomainException\DomainRecordNotFoundException;

class AkunRekeningNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Akun rekening tidak ditemukan';
}
