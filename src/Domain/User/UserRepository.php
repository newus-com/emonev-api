<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\UserBadRequestException;
use App\Domain\Permission\PermissionNotFoundException;

interface UserRepository
{
    /**
     * @param array|null $options
     * @return User[]
     */
    public function findAll(array|null $options): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findOneById(int $id): User;

    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function findOneByEmail(string $email): User;


    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findOneByIdFullJoin(int $id): User;

    /**
     * @param User $user
     * @return User
     * @throws UserNotFoundException
     */
    public function createUser(User $user): User;

    /**
     * @param int $id
     * @return bool
     * @throws UserNotFoundException
     */
    public function deleteUser(int $id): bool;

    /**
     * @param int $id
     * @param User $user
     * @return User
     * @throws UserNotFoundException
     */
    public function updateUser(int $id, User $user): User;

    /**
     * @param int $id
     * @param User $user
     * @return User
     * @throws UserNotFoundException
     */
    public function addSpecialPermissionUser(int $id,int $idPermission): User;

    /**
     * @param int $id
     * @param int $idPermission
     * @param User $user
     * @return bool
     * @throws UserNotFoundException
     */
    public function deleteSpecialPermissionUser(int $id,int $idPermission): bool;

    /**
     * @param int $id
     * @param int $idRole
     * @return User
     * @throws UserNotFoundException
     */
    public function addUserRole(int $id,int $idRole): User;
    
    /**
     * @param int $id
     * @param int $idRole
     * @param User $user
     * @return bool
     * @throws UserNotFoundException
     */
    public function deleteRoleUser(int $id,int $idPermission): bool;

    /**
     * @param int $id
     * @return array
     * @throws UserNotFoundException
     * @throws PermissionNotFoundException
     * */
    public function getAllPermissionOneUser(int $id): array;

    /**
     * @param int $id
     * @return array
     * @throws UserNotFoundException
     * @throws PermissionNotFoundException
     * */
    public function getAllRoleOneUser(int $id): array;

    public function getAllPermission(int $id): array;
    public function getAllDinasFromUser(int $id): array;
}
