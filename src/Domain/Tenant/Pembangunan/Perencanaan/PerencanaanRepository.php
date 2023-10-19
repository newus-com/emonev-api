<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Pembangunan\Perencanaan;

interface PerencanaanRepository
{
    /**
     * @param array|null $options
     * @return Perencanaan[]
     */
    public function findAll(array|null $options): array;

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function findOneByDetailKetSubDpaId(int $id);

    /**
     * @param Perencanaan $rencana
     * @return void
     * @throws Exception
     */
    public function createORupdate(Perencanaan $rencana);

    /**
     * @param int $id
     * @param Perencanaan $rencana
     * @return void
     * @throws Exception
     */
    public function update(int $id, $rencana);

    public function addKomponen(int $rencanaPembangunanId, int $komponenPembangunanId);
    public function findAllRunning(?array $options): array;
    public function findOneRunning(int $id);
    public function updateBlanko(int $id, array $blanko);

}
