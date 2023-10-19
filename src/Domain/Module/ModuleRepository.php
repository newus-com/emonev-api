<?php

declare(strict_types=1);

namespace App\Domain\Module;

use App\Domain\Dinas\ModuleNotFoundException;

interface ModuleRepository
{
    /**
     * @return Module[]
     */
    public function findAll(): array;


    /**
     * @param int $id
     * @return Module
     * @throws ModuleNotFoundException
     */
    public function findOneById(int $id): Module;

    /**
     * @param int $id
     * @return Module
     * @throws ModuleNotFoundException
     */
    public function findOneByIdWithPermission(int $id): Module;
}
