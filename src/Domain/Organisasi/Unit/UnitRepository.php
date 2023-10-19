<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Unit;

use App\Domain\Organisasi\Unit\Unit;
use App\Domain\Organisasi\Unit\UnitNotFoundException;
use App\Domain\Organisasi\Unit\UnitFailedInsertException;
use App\Domain\Organisasi\Unit\UnitFailedUpdateException;
use App\Domain\Organisasi\Organisasi\OrganisasiNotFoundException;

interface UnitRepository
{
    /**
     * @param array|null $options
     * @return Unit[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Unit
     * @throws UnitNotFoundException
     */
    public function findOneById(int $id): Unit;

    /**
     * @param Unit $organisasi
     * @return Unit
     * @throws UnitFailedInsertException
     * @throws OrganisasiNotFoundException
     */
    public function create(Unit $organisasi): Unit;

    /**
     * @param int $id
     * @param Unit $organisasi
     * @return Unit
     * @throws UnitNotFoundException
     * @throws UnitFailedUpdateException
     * @throws OrganisasiNotFoundException
     */
    public function update(int $id, Unit $organisasi): Unit;

    /**
     * @param int $id
     * @return bool
     * @throws UnitNotFoundException
     */
    public function delete(int $id): bool;
}
