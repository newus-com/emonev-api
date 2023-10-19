<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\Dpa;

use App\Domain\Tenant\Anggaran\Dpa\Dpa;
use App\Domain\Tenant\Anggaran\Dpa\DpaNotFoundException;
use App\Domain\Tenant\Anggaran\Dpa\DpaFailedInsertException;
use App\Domain\Tenant\Anggaran\Dpa\DpaFailedUpdateException;

interface DpaRepository
{
    /**
     * @param array|null $options
     * @return Dpa[]
     */
    public function findAll(array|null $options): array;

    /**
     * @param array|null $options
     * @return Dpa[]
     */
    public function findAllPengambilan(array|null $options): array;

    public function createOrUpdateRencanaPenarikan($dpaId,$rencanaPenarikan);


    public function getDetailPagu($dpaId);


    public function findPenggunaAnggaran(int $id);

    public function updatePenggunaAnggaran(int $id, $penggunaAnggaran);


    public function findTandaTangan(int $id);

    public function updateTandaTangan(int $id, $ttdDpa);

    /**
     * @param int $id
     * @return void
     * @throws DpaNotFoundException
     */
    public function findOneById(int $id);


    /**
     * @param string $noDpa
     * @return Dpa
     * @throws DpaNotFoundException
     */
    public function findOneByNoDpa(string $noDpa): Dpa;

    public function findDetailByid(int $id);

    /**
     * @param Dpa $dpa
     * @return array
     * @throws DpaFailedInsertException
     * @throws UrusanNotFoundException
     */
    public function create(Dpa $dpa);

    /**
     * @param int $id
     * @param Dpa $dpa
     * @return void
     * @throws DpaNotFoundException
     * @throws DpaFailedUpdateException
     * @throws UrusanNotFoundException
     */
    public function update(int $id, Dpa $dpa);
    public function updateRincian(int $id, Dpa $dpa);

    /**
     * @param int $id
     * @return bool
     * @throws DpaNotFoundException
     */
    public function delete(int $id): bool;
}
