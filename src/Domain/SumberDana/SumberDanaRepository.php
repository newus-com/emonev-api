<?php

declare(strict_types=1);

namespace App\Domain\SumberDana;

use App\Domain\SumberDana\SumberDanaNotFoundException;
use App\Domain\SumberDana\SumberDanaFailedInsertException;
use App\Domain\SumberDana\SumberDanaFailedUpdateException;

interface SumberDanaRepository
{
    /**
     * @param array|null $options
     * @return SumberDana[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return SumberDana
     * @throws SumberDanaNotFoundException
     */
    public function findOneById(int $id): SumberDana;

    /**
     * @param SumberDana $sumberDana
     * @return SumberDana
     * @throws SumberDanaFailedInsertException
     */
    public function create(SumberDana $sumberDana): SumberDana;

    /**
     * @param int $id
     * @param SumberDana $sumberDana
     * @return SumberDana
     * @throws SumberDanaNotFoundException
     * @throws SumberDanaFailedUpdateException
     */
    public function update(int $id, SumberDana $sumberDana): SumberDana;

    /**
     * @param int $id
     * @return bool
     * @throws SumberDanaNotFoundException
     */
    public function delete(int $id): bool;
}
