<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Anggaran\RencanaPengambilan;

use App\Domain\Tenant\Anggaran\RencanaPengambilan\RencanaPengambilan;

interface RencanaPengambilanRepository
{
    /**
     * @param int $subDpaId
     * @param string $bulan
     * @return []
     */
    public function findAllBySubDpa(int $subDpaId, string $bulan): array;

    /**
     * @param array $rencabaPengambilan
     * @return array
     */
    public function createRealisasi(array $rencabaPengambilan);

    /**
     * @param int $id
     * @param RencanaPengambilan $rencabaPengambilan
     * @return RencanaPengambilan
     */
    public function updateRealisasi(int $id,RencanaPengambilan $rencabaPengambilan): RencanaPengambilan;

    public function findOneById(int $id): RencanaPengambilan;
}
