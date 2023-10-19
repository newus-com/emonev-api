<?php

declare(strict_types=1);

namespace App\Application\Actions\Perencanaan\Program;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Perencanaan\Program\ProgramRepository;
use App\Application\Validator\ValidInterface;

abstract class ProgramAction extends Action
{
    protected ProgramRepository $programRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,ProgramRepository $programRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->programRepository = $programRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
