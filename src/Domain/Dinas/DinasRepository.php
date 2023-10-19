<?php

declare(strict_types=1);

namespace App\Domain\Dinas;

use App\Domain\Dinas\DinasFailedInsertException;

interface DinasRepository
{
    /**
     * @param array|null $options
     * @return Dinas[]
     */
    public function findAll(array|null $options): array;

    /**
     * @param int $id
     * @return Dinas
     * @throws DinasNotFoundException
     */
    public function findOneById(int $id): Dinas;


    /**
     * @param int $id
     * @return Dinas
     * @throws DinasNotFoundException
     */
    public function findOneByIdWithRole(int $id): Dinas;

    /**
     * @param string $name
     * @param string $email
     * @param string $noHp
     * @param string $address
     * @param string $logo
     * @return int|bool|string
     * @throws DinasFailedInsertException
     */
    public function create(string $name, string $email, string $noHp, string $address, string $logo): int|bool|string;

    /**
     * @param int $id
     * @param Dinas $dinas
     * @return Dinas
     * @throws UserNotFoundException
     */
    public function updateDinas(int $id, Dinas $dinas): Dinas;
    
     /**
     * @param int $id
     * @return bool
     * @throws DinasNotFoundException
     */
    public function deleteDinas(int $id): bool;

    /**
     * @param int $id
     * @return array
     * @throws DinasNotFoundException
     * @throws RoleNotFoundException
     * */
    public function getAllRoleInDinas(int $id): array;

    /**
     * @param int $id
     * @return array
     * @throws DinasNotFoundException
     * @throws UserNotFoundException
     * */
    public function getAllUserInDinas(int $id): array;

    /**
     * @param int $id
     * @return array
     * @throws DinasNotFoundException
     * @throws UserNotFoundException
     * */
    public function getAllDinasInUser(int $id): array;

}
