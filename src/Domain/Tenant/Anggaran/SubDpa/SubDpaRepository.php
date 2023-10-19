<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\SubDpa;

use App\Domain\Tenant\Anggaran\SubDpa\SubDpa;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaNotFoundException;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaFailedInsertException;
use App\Domain\Tenant\Anggaran\SubDpa\SubDpaFailedUpdateException;

interface SubDpaRepository
{
    /**
     * @param array|null $options
     * @return SubDpa[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return void
     * @throws SubDpaNotFoundException
     */
    public function findOneById(int $id);
    public function findOneByIdAndDetail(int $id);

    

    public function findAllByDpaId(int $dpaId);

    /**
     * @param array $subDpa
     * @return array
     * @throws SubDpaFailedInsertException
     */
    public function create(array $subDpa);

    /**
     * @param int $id
     * @param array $SubDpa
     * @return void
     * @throws SubDpaNotFoundException
     * @throws SubDpaFailedUpdateException
     * @throws UrusanNotFoundException
     */
    public function update(int $id, array $SubDpa);

    /**
     * @param int $id
     * @return bool
     * @throws SubDpaNotFoundException
     */
    public function delete(int $id): bool;
}
