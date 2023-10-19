<?php

declare(strict_types=1);

namespace App\Application\Actions\Organisasi\Unit;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Organisasi\Unit\UnitRepository;
use App\Application\Validator\ValidInterface;

abstract class UnitAction extends Action
{
    protected UnitRepository $unitRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,UnitRepository $unitRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->unitRepository = $unitRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
