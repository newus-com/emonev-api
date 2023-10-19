<?php

declare(strict_types=1);

namespace App\Domain\Rekening\KelompokRekening;

use App\Domain\DomainException\DomainRecordNotFoundException;

class KelompokRekeningNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Kelompok rekening tidak ditemukan';
}
