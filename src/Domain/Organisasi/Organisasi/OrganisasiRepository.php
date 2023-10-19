<?php

declare(strict_types=1);

namespace App\Domain\Organisasi\Organisasi;

use App\Domain\Organisasi\Organisasi\Organisasi;
use App\Domain\Dinas\OrganisasiNotFoundException;
use App\Domain\Organisasi\OrganisasiFailedInsertException;
use App\Domain\Organisasi\OrganisasiFailedUpdateException;

interface OrganisasiRepository
{
    /**
     * @param array|null $options
     * @return Organisasi[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Organisasi
     * @throws OrganisasiNotFoundException
     */
    public function findOneById(int $id): Organisasi;

    /**
     * @param Organisasi $organisasi
     * @return Organisasi
     * @throws OrganisasiFailedInsertException
     * @throws BidangNotFoundException
     */
    public function create(Organisasi $organisasi): Organisasi;

    /**
     * @param int $id
     * @param Organisasi $organisasi
     * @return Organisasi
     * @throws OrganisasiNotFoundException
     * @throws OrganisasiFailedUpdateException
     * @throws BidangNotFoundException
     */
    public function update(int $id, Organisasi $organisasi): Organisasi;

    /**
     * @param int $id
     * @return bool
     * @throws OrganisasiNotFoundException
     */
    public function delete(int $id): bool;
}
