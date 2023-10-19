<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Bidang;

use App\Domain\Perencanaan\Bidang\Bidang;
use App\Domain\BIdang\BidangNotFoundException;
use App\Domain\Bidang\BidangFailedInsertException;
use App\Domain\Bidang\BidangFailedUpdateException;

interface BidangRepository
{
    /**
     * @param array|null $options
     * @return Bidang[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Bidang
     * @throws BidangNotFoundException
     */
    public function findOneById(int $id): Bidang;

    /**
     * @param Bidang $bidang
     * @return Bidang
     * @throws BidangFailedInsertException
     * @throws UrusanNotFoundException
     */
    public function create(Bidang $bidang): Bidang;

    /**
     * @param int $id
     * @param Bidang $bidang
     * @return Bidang
     * @throws BidangNotFoundException
     * @throws BidangFailedUpdateException
     * @throws UrusanNotFoundException
     */
    public function update(int $id, Bidang $bidang): Bidang;

    /**
     * @param int $id
     * @return bool
     * @throws BidangNotFoundException
     */
    public function delete(int $id): bool;
}
