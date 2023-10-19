<?php

declare(strict_types=1);

namespace App\Domain\Rekening\ObjekRekening;

use App\Domain\Dinas\ObjekRekeningNotFoundException;
use App\Domain\Rekening\ObjekRekening\ObjekRekening;
use App\Domain\ObjekRekening\ObjekRekeningFailedInsertException;
use App\Domain\ObjekRekening\ObjekRekeningFailedUpdateException;
use App\Domain\Rekening\JenisRekening\JenisRekeningNotFoundException;

interface ObjekRekeningRepository
{
    /**
     * @param array|null $options
     * @return ObjekRekening[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return ObjekRekening
     * @throws ObjekRekeningNotFoundException
     */
    public function findOneById(int $id): ObjekRekening;

    /**
     * @param ObjekRekening $objekRekenig
     * @return ObjekRekening
     * @throws ObjekRekeningFailedInsertException
     * @throws JenisRekeningNotFoundException
     */
    public function create(ObjekRekening $objekRekenig): ObjekRekening;

    /**
     * @param int $id
     * @param ObjekRekening $objekRekenig
     * @return ObjekRekening
     * @throws ObjekRekeningNotFoundException
     * @throws ObjekRekeningFailedUpdateException
     * @throws JenisRekeningNotFoundException
     */
    public function update(int $id, ObjekRekening $objekRekenig): ObjekRekening;

    /**
     * @param int $id
     * @return bool
     * @throws ObjekRekeningNotFoundException
     */
    public function delete(int $id): bool;
}
