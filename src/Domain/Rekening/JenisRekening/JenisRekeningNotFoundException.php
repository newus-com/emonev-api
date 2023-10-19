<?php

declare(strict_types=1);

namespace App\Domain\Rekening\JenisRekening;

use App\Domain\DomainException\DomainRecordNotFoundException;

class JenisRekeningNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Jenis rekening tidak ditemukan';
}
