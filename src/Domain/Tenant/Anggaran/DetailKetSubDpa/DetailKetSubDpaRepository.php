<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\DetailKetSubDpa;

use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpa;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaNotFoundException;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaFailedInsertException;
use App\Domain\Tenant\Anggaran\DetailKetSubDpa\DetailKetSubDpaFailedUpdateException;

interface DetailKetSubDpaRepository
{
    /**
     * @param array|null $options
     * @return DetailKetSubDpa[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return void
     * @throws DetailKetSubDpaNotFoundException
     */
    public function findOneById(int $id);

    /**
     * @param DetailKetSubDpa $detailKetSubDpa
     * @return DetailKetSubDpa
     * @throws DetailKetSubDpaFailedInsertException
     * @throws UrusanNotFoundException
     */
    public function create(DetailKetSubDpa $detailKetSubDpa): DetailKetSubDpa;

    /**
     * @param int $id
     * @param DetailKetSubDpa $detailKetSubDpa
     * @return void
     * @throws DetailKetSubDpaNotFoundException
     * @throws DetailKetSubDpaFailedUpdateException
     * @throws UrusanNotFoundException
     */
    public function update(int $id, DetailKetSubDpa $detailKetSubDpa);

    /**
     * @param int $id
     * @return bool
     * @throws DetailKetSubDpaNotFoundException
     */
    public function delete(int $id): bool;
}
