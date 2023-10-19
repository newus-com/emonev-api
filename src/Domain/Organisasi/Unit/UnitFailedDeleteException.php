<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Unit;

use App\Domain\DomainException\DomainRecordFailedDeleteException;

class UnitFailedDeleteException extends DomainRecordFailedDeleteException
{
    public $message = 'Gagal menghapus unit';
}
