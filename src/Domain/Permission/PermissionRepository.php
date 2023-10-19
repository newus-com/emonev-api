<?php

declare(strict_types=1);

namespace App\Domain\Permission;

use App\Domain\Dinas\PermissionNotFoundException;

interface PermissionRepository
{
    /**
     * @return Permission[]
     */
    public function findAll(): array;


    /**
     * @param int $id
     * @return Permission
     * @throws PermissionNotFoundException
     */
    public function findOneById(int $id): Permission;
}
