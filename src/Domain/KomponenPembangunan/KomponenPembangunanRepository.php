<?php

declare(strict_types=1);

namespace App\Domain\KomponenPembangunan;

use App\Domain\KomponenPembangunan\KomponenPembangunanNotFoundException;
use App\Domain\KomponenPembangunan\KomponenPembangunanFailedInsertException;
use App\Domain\KomponenPembangunan\KomponenPembangunanFailedUpdateException;

interface KomponenPembangunanRepository
{
    /**
     * @param array|null $options
     * @return KomponenPembangunan[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return KomponenPembangunan
     * @throws KomponenPembangunanNotFoundException
     */
    public function findOneById(int $id): KomponenPembangunan;

    /**
     * @param KomponenPembangunan $komponenPembangunan
     * @return KomponenPembangunan
     * @throws KomponenPembangunanFailedInsertException
     */
    public function create(KomponenPembangunan $komponenPembangunan): KomponenPembangunan;

    /**
     * @param int $id
     * @param KomponenPembangunan $komponenPembangunan
     * @return KomponenPembangunan
     * @throws KomponenPembangunanNotFoundException
     * @throws KomponenPembangunanFailedUpdateException
     */
    public function update(int $id, KomponenPembangunan $komponenPembangunan): KomponenPembangunan;

    /**
     * @param int $id
     * @return bool
     * @throws KomponenPembangunanNotFoundException
     */
    public function delete(int $id): bool;
}
