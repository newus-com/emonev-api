<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\KetSubDpa;

use App\Domain\DomainException\DomainRecordNotFoundException;

class JumlahAnggaranKetException extends DomainRecordNotFoundException
{
    public $message = 'Jumlah anggaran tidak boleh lebih kecil dari total rincian belanja';
}
