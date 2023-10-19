<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\SubKegiatan;

use App\Domain\Perencanaan\SubKegiatan\SubKegiatan;
use App\Domain\Dinas\SubKegiatanNotFoundException;
use App\Domain\SubKegiatan\SubKegiatanFailedInsertException;
use App\Domain\SubKegiatan\SubKegiatanFailedUpdateException;
use App\Domain\Perencanaan\Kegiatan\KegiatanNotFoundException;

interface SubKegiatanRepository
{
    /**
     * @param array|null $options
     * @return SubKegiatan[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return SubKegiatan
     * @throws SubKegiatanNotFoundException
     */
    public function findOneById(int $id): SubKegiatan;

    /**
     * @param SubKegiatan $subKegiatan
     * @return SubKegiatan
     * @throws SubKegiatanFailedInsertException
     * @throws KegiatanNotFoundException
     */
    public function create(SubKegiatan $subKegiatan): SubKegiatan;

    /**
     * @param int $id
     * @param SubKegiatan $subKegiatan
     * @return SubKegiatan
     * @throws SubKegiatanNotFoundException
     * @throws SubKegiatanFailedUpdateException
     * @throws KegiatanNotFoundException
     */
    public function update(int $id, SubKegiatan $subKegiatan): SubKegiatan;

    /**
     * @param int $id
     * @return bool
     * @throws SubKegiatanNotFoundException
     */
    public function delete(int $id): bool;
}
