<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Urusan;

use App\Domain\Dinas\UrusanNotFoundException;
use App\Domain\Urusan\UrusanFailedInsertException;
use App\Domain\Urusan\UrusanFailedUpdateException;

interface UrusanRepository
{
    /**
     * @param array|null $options
     * @return Urusan[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Urusan
     * @throws UrusanNotFoundException
     */
    public function findOneById(int $id): Urusan;

    /**
     * @param Urusan $urusan
     * @return Urusan
     * @throws UrusanFailedInsertException
     */
    public function create(Urusan $urusan): Urusan;

    /**
     * @param int $id
     * @param Urusan $urusan
     * @return Urusan
     * @throws UrusanNotFoundException
     * @throws UrusanFailedUpdateException
     */
    public function update(int $id, Urusan $urusan): Urusan;

    /**
     * @param int $id
     * @return bool
     * @throws UrusanNotFoundException
     */
    public function delete(int $id): bool;
}
