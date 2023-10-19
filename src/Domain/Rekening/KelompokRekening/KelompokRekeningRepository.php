<?php

declare(strict_types=1);

namespace App\Domain\Rekening\KelompokRekening;

use App\Domain\Dinas\KelompokRekeningNotFoundException;
use App\Domain\Rekening\KelompokRekening\KelompokRekening;
use App\Domain\Rekening\AkunRekening\AkunRekeningNotFoundException;
use App\Domain\KelompokRekening\KelompokRekeningFailedInsertException;
use App\Domain\KelompokRekening\KelompokRekeningFailedUpdateException;

interface KelompokRekeningRepository
{
    /**
     * @param array|null $options
     * @return KelompokRekening[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return KelompokRekening
     * @throws KelompokRekeningNotFoundException
     */
    public function findOneById(int $id): KelompokRekening;

    /**
     * @param KelompokRekening $kelompokRekenig
     * @return KelompokRekening
     * @throws KelompokRekeningFailedInsertException
     * @throws AkunRekeningNotFoundException
     */
    public function create(KelompokRekening $kelompokRekenig): KelompokRekening;

    /**
     * @param int $id
     * @param KelompokRekening $kelompokRekenig
     * @return KelompokRekening
     * @throws KelompokRekeningNotFoundException
     * @throws KelompokRekeningFailedUpdateException
     * @throws AkunRekeningNotFoundException
     */
    public function update(int $id, KelompokRekening $kelompokRekenig): KelompokRekening;

    /**
     * @param int $id
     * @return bool
     * @throws KelompokRekeningNotFoundException
     */
    public function delete(int $id): bool;
}
