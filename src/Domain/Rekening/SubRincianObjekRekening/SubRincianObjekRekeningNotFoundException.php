<?php

declare(strict_types=1);

namespace App\Domain\Rekening\SubRincianObjekRekening;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SubRincianObjekRekeningNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Sub rincian objek rekening tidak ditemukan';
}
