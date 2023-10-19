<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Kegiatan;

use App\Domain\Perencanaan\Kegiatan\Kegiatan;
use App\Domain\Dinas\KegiatanNotFoundException;
use App\Domain\Kegiatan\KegiatanFailedInsertException;
use App\Domain\Kegiatan\KegiatanFailedUpdateException;
use App\Domain\Perencanaan\Program\ProgramNotFoundException;

interface KegiatanRepository
{
    /**
     * @param array|null $options
     * @return Kegiatan[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Kegiatan
     * @throws KegiatanNotFoundException
     */
    public function findOneById(int $id): Kegiatan;

    /**
     * @param Kegiatan $kegiatan
     * @return Kegiatan
     * @throws KegiatanFailedInsertException
     * @throws ProgramNotFoundException
     */
    public function create(Kegiatan $kegiatan): Kegiatan;

    /**
     * @param int $id
     * @param Kegiatan $kegiatan
     * @return Kegiatan
     * @throws KegiatanNotFoundException
     * @throws KegiatanFailedUpdateException
     * @throws ProgramNotFoundException
     */
    public function update(int $id, Kegiatan $kegiatan): Kegiatan;

    /**
     * @param int $id
     * @return bool
     * @throws KegiatanNotFoundException
     */
    public function delete(int $id): bool;
}
