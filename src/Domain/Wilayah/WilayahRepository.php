<?php

declare(strict_types=1);

namespace App\Domain\Wilayah;

use App\Domain\Wilayah\WilayahNotFoundException;
use App\Domain\Wilayah\WilayahFailedInsertException;
use App\Domain\Wilayah\WilayahFailedUpdateException;

interface WilayahRepository
{
    /**
     * @param array|null $options
     * @return Wilayah[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Wilayah
     * @throws WilayahNotFoundException
     */
    public function findOneById(int $id): Wilayah;

    /**
     * @param Wilayah $wilayah
     * @return Wilayah
     * @throws WilayahFailedInsertException
     */
    public function create(Wilayah $wilayah): Wilayah;

    /**
     * @param int $id
     * @param Wilayah $wilayah
     * @return Wilayah
     * @throws WilayahNotFoundException
     * @throws WilayahFailedUpdateException
     */
    public function update(int $id, Wilayah $wilayah): Wilayah;

    /**
     * @param int $id
     * @return bool
     * @throws WilayahNotFoundException
     */
    public function delete(int $id): bool;
}
