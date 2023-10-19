<?php

declare(strict_types=1);

namespace App\Domain\Rekening\RincianObjekRekening;

use App\Domain\Dinas\RincianObjekRekeningNotFoundException;
use App\Domain\Rekening\RincianObjekRekening\RincianObjekRekening;
use App\Domain\RincianObjekRekening\RincianObjekRekeningFailedInsertException;
use App\Domain\RincianObjekRekening\RincianObjekRekeningFailedUpdateException;
use App\Domain\Rekening\ObjekRekening\ObjekRekeningNotFoundException;

interface RincianObjekRekeningRepository
{
    /**
     * @param array|null $options
     * @return RincianObjekRekening[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return RincianObjekRekening
     * @throws RincianObjekRekeningNotFoundException
     */
    public function findOneById(int $id): RincianObjekRekening;

    /**
     * @param RincianObjekRekening $objekRekenig
     * @return RincianObjekRekening
     * @throws RincianObjekRekeningFailedInsertException
     * @throws ObjekRekeningNotFoundException
     */
    public function create(RincianObjekRekening $objekRekenig): RincianObjekRekening;

    /**
     * @param int $id
     * @param RincianObjekRekening $objekRekenig
     * @return RincianObjekRekening
     * @throws RincianObjekRekeningNotFoundException
     * @throws RincianObjekRekeningFailedUpdateException
     * @throws ObjekRekeningNotFoundException
     */
    public function update(int $id, RincianObjekRekening $objekRekenig): RincianObjekRekening;

    /**
     * @param int $id
     * @return bool
     * @throws RincianObjekRekeningNotFoundException
     */
    public function delete(int $id): bool;
}
