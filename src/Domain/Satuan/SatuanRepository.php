<?php

declare(strict_types=1);

namespace App\Domain\Satuan;

use App\Domain\Satuan\SatuanNotFoundException;
use App\Domain\Satuan\SatuanFailedInsertException;
use App\Domain\Satuan\SatuanFailedUpdateException;

interface SatuanRepository
{
    /**
     * @param array|null $options
     * @return Satuan[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Satuan
     * @throws SatuanNotFoundException
     */
    public function findOneById(int $id): Satuan;

    /**
     * @param Satuan $satuan
     * @return Satuan
     * @throws SatuanFailedInsertException
     */
    public function create(Satuan $satuan): Satuan;

    /**
     * @param int $id
     * @param Satuan $satuan
     * @return Satuan
     * @throws SatuanNotFoundException
     * @throws SatuanFailedUpdateException
     */
    public function update(int $id, Satuan $satuan): Satuan;

    /**
     * @param int $id
     * @return bool
     * @throws SatuanNotFoundException
     */
    public function delete(int $id): bool;
}
