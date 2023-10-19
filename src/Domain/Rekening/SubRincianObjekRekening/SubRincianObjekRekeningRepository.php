<?php

declare(strict_types=1);

namespace App\Domain\Rekening\SubRincianObjekRekening;

use App\Domain\Dinas\SubRincianObjekRekeningNotFoundException;
use App\Domain\Rekening\SubRincianObjekRekening\SubRincianObjekRekening;
use App\Domain\SubRincianObjekRekening\SubRincianObjekRekeningFailedInsertException;
use App\Domain\SubRincianObjekRekening\SubRincianObjekRekeningFailedUpdateException;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekeningNotFoundException;

interface SubRincianObjekRekeningRepository
{
    /**
     * @param array|null $options
     * @return SubRincianObjekRekening[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return SubRincianObjekRekening
     * @throws SubRincianObjekRekeningNotFoundException
     */
    public function findOneById(int $id): SubRincianObjekRekening;

    /**
     * @param SubRincianObjekRekening $objekRekenig
     * @return SubRincianObjekRekening
     * @throws SubRincianObjekRekeningFailedInsertException
     * @throws RincianObjekRekeningNotFoundException
     */
    public function create(SubRincianObjekRekening $objekRekenig): SubRincianObjekRekening;

    /**
     * @param int $id
     * @param SubRincianObjekRekening $objekRekenig
     * @return SubRincianObjekRekening
     * @throws SubRincianObjekRekeningNotFoundException
     * @throws SubRincianObjekRekeningFailedUpdateException
     * @throws RincianObjekRekeningNotFoundException
     */
    public function update(int $id, SubRincianObjekRekening $objekRekenig): SubRincianObjekRekening;

    /**
     * @param int $id
     * @return bool
     * @throws SubRincianObjekRekeningNotFoundException
     */
    public function delete(int $id): bool;
}
