<?php

declare(strict_types=1);

namespace App\Domain\Tahun;

use App\Domain\Tahun\TahunNotFoundException;
use App\Domain\Tahun\TahunFailedInsertException;
use App\Domain\Tahun\TahunFailedUpdateException;

interface TahunRepository
{
    /**
     * @param array|null $options
     * @return Tahun[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Tahun
     * @throws TahunNotFoundException
     */
    public function findOneById(int $id): Tahun;

    /**
     * @param Tahun $tahun
     * @return Tahun
     * @throws TahunFailedInsertException
     */
    public function create(Tahun $tahun): Tahun;

    /**
     * @param int $id
     * @param Tahun $tahun
     * @return Tahun
     * @throws TahunNotFoundException
     * @throws TahunFailedUpdateException
     */
    public function update(int $id, Tahun $tahun): Tahun;

    /**
     * @param int $id
     * @param Tahun $tahun
     * @return Tahun
     * @throws TahunNotFoundException
     * @throws TahunFailedUpdateException
     */
    public function active(int $id, Tahun $tahun): Tahun;

    /**
     * @param int $id
     * @return bool
     * @throws TahunNotFoundException
     */
    public function delete(int $id): bool;
}
