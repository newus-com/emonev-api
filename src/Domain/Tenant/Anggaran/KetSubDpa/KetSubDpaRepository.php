<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\KetSubDpa;

use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpa;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaNotFoundException;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaFailedInsertException;
use App\Domain\Tenant\Anggaran\KetSubDpa\KetSubDpaFailedUpdateException;

interface KetSubDpaRepository
{
    /**
     * @param array|null $options
     * @return KetSubDpa[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return void
     * @throws KetSubDpaNotFoundException
     */
    public function findOneById(int $id);

    /**
     * @param array $ketSubDpa
     * @return
     * @throws KetSubDpaFailedInsertException
     * @throws UrusanNotFoundException
     */
    public function create(array $KetSubDpa);

    /**
     * @param int $id
     * @param $ketSubDpa
     * @return void
     * @throws KetSubDpaNotFoundException
     * @throws KetSubDpaFailedUpdateException
     * @throws UrusanNotFoundException
     */
    public function update(int $id, $ketSubDpa);

    /**
     * @param int $id
     * @return bool
     * @throws KetSubDpaNotFoundException
     */
    public function delete(int $id): bool;
}
