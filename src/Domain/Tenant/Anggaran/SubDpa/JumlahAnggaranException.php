<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\SubDpa;

use App\Domain\DomainException\DomainRecordNotFoundException;

class JumlahAnggaranException extends DomainRecordNotFoundException
{
    public $message = 'Jumlah rincian belanja tidak boleh lebih dari anggaran';
}
