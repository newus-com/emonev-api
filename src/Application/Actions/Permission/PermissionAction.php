<?php

declare(strict_types=1);

namespace App\Application\Actions\Permission;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Application\Token\TokenInterface;
use App\Domain\Permission\PermissionRepository;
use App\Application\Validator\ValidInterface;

abstract class PermissionAction extends Action
{
    protected PermissionRepository $permissionRepository;

    protected ValidInterface $valid;

    protected TokenInterface $token;

    public function __construct(LoggerInterface $logger ,PermissionRepository $permissionRepository, ValidInterface $valid, TokenInterface $token)
    {
        parent::__construct($logger);
        $this->permissionRepository = $permissionRepository;
        $this->valid = $valid;
        $this->token = $token;
    }
}
