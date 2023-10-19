<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Application\Validator\ValidInterface;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
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
