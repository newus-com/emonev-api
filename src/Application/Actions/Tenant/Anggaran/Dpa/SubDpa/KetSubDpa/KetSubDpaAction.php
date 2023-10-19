<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa\KetSubDpa;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaRepository;
use App\Application\Validator\ValidInterface;

abstract class KetSubDpaAction extends Action
{
    protected KetSubDpaRepository $ketSubDpaRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,KetSubDpaRepository $ketSubDpaRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->ketSubDpaRepository = $ketSubDpaRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
