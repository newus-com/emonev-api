<?php

declare(strict_types=1);

namespace App\Application\Actions\Tenant\Anggaran\Dpa;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Tenant\Anggaran\Dpa\DpaRepository;
use App\Application\Validator\ValidInterface;

abstract class DpaAction extends Action
{
    protected DpaRepository $dpaRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,DpaRepository $dpaRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->dpaRepository = $dpaRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
