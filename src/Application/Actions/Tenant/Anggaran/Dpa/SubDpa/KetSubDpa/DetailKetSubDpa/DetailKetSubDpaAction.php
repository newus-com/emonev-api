<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa\DetailKetSubDpa;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaRepository;
use App\Application\Validator\ValidInterface;

abstract class DetailKetSubDpaAction extends Action
{
    protected DetailKetSubDpaRepository $detailKetSubDpaRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,DetailKetSubDpaRepository $detailKetSubDpaRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->detailKetSubDpaRepository = $detailKetSubDpaRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
