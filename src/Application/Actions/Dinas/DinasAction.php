<?php

declare(strict_types=1);

namespace App\Application\Actions\Dinas;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Dinas\DinasRepository;
use App\Application\Validator\ValidInterface;

abstract class DinasAction extends Action
{
    protected DinasRepository $dinasRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,DinasRepository $dinasRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->dinasRepository = $dinasRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
