<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Laporan;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use Psr\Container\ContainerInterface;
use App\Application\Token\TokenInterface;
use App\Application\Validator\ValidInterface;
use App\Domain\Tenant\Anggaran\Dpa\DpaRepository;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaRepository;
use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilan;
use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilanRepository;

abstract class LaporanAction extends Action
{
    protected DpaRepository $dpaRepository;
    protected SubDpaRepository $subDpaRepository;
    protected RencanaPengambilanRepository $rencanaPengambilanRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;


    public function __construct(LoggerInterface $logger, DpaRepository $dpaRepository, SubDpaRepository $subDpaRepository, RencanaPengambilanRepository $rencanaPengambilanRepository,ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->dpaRepository = $dpaRepository;
        $this->subDpaRepository = $subDpaRepository;
        $this->rencanaPengambilanRepository = $rencanaPengambilanRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
