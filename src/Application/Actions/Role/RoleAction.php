<?php

declare(strict_types=1);

namespace App\Application\Actions\Role;

use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Application\Validator\ValidInterface;
use App\Domain\Role\RoleRepository;
use Psr\Log\LoggerInterface;

abstract class RoleAction extends Action
{
    protected RoleRepository $roleRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger, RoleRepository $roleRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->roleRepository = $roleRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
