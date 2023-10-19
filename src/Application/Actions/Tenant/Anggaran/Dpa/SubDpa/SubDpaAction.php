<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa\SubDpa;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaRepository;
use App\Application\Validator\ValidInterface;

abstract class SubDpaAction extends Action
{
    protected SubDpaRepository $subDpaRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,SubDpaRepository $subDpaRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->subDpaRepository = $subDpaRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
