<?php

declare(strict_types=1);

namespace App\Domain\Rekening\ObjekRekening;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ObjekRekeningNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Objek rekening tidak ditemukan';
}
