<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\User\UserRepository;
use App\Application\Validator\ValidInterface;

abstract class AuthAction extends Action
{
    protected UserRepository $userRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
