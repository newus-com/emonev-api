<?php

declare(strict_types=1);

namespace App\Domain\Role;

use App\Domain\Dinas\RoleNotFoundException;

interface RoleRepository
{
    /**
     * @param array|null $options
     * @return Role[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findOneById(int $id): Role;

    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findOneByIdWithModule(int $id): Role;


    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findOneByIdWithModuleAndPermission(int $id): Role;

    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findOneByIdWithPermission(int $id): Role;

    /**
     * @param Role $role
     * @return Role
     * @throws RoleBadRequestException
     */
    public function createRole(Role $role): Role;

    /**
     * @param int $id
     * @return bool
     * @throws RoleNotFoundException
     */
    public function deleteRole(int $id): bool;

    /**
     * @param int $id
     * @param Role $role
     * @return Role
     * @throws RoleNotFoundException
     */
    public function updateRole(int $id, Role $role): Role;

    /**
     * @param int $id
     * @param int $idPermission
     * @param Role $role
     * @return Role
     * @throws RoleNotFoundException
     */
    public function addRolePermission(int $id, int $idPermission): Role;

    /**
     * @param int $id
     * @param int $idPermission
     * @return bool
     * @throws RoleNotFoundException
     * */
    public function deleteRolePermission(int $id, int $idPermission): bool;

    /**
     * @param int $id
     * @return array
     * @throws UserNotFoundException
     * @throws PermissionNotFoundException
     * */
    public function getAllPermissionOneRole(int $id): array;
}
