<?php

declare(strict_types=1);

namespace App\Domain\Rekening\RincianObjekRekening;

use App\Domain\DomainException\DomainRecordNotFoundException;

class RincianObjekRekeningNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Rincian objek rekening tidak ditemukan';
}
