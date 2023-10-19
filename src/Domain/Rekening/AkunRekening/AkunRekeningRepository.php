<?php

declare(strict_types=1);

namespace App\Domain\Rekening\AkunRekening;

use App\Domain\Rekening\AkunRekening\AkunRekening;
use App\Domain\Rekening\AkunRekening\AkunRekeningNotFoundException;
use App\Domain\Rekening\AkunRekening\AkunRekeningFailedInsertException;
use App\Domain\Rekening\AkunRekening\AkunRekeningFailedUpdateException;

interface AkunRekeningRepository
{
    /**
     * @param array|null $options
     * @return AkunRekening[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return AkunRekening
     * @throws AkunRekeningNotFoundException
     */
    public function findOneById(int $id): AkunRekening;

    /**
     * @param AkunRekening $akunRekening
     * @return AkunRekening
     * @throws AkunRekeningFailedInsertException
     */
    public function create(AkunRekening $akunRekening): AkunRekening;

    /**
     * @param int $id
     * @param AkunRekening $akunRekening
     * @return AkunRekening
     * @throws AkunRekeningNotFoundException
     * @throws AkunRekeningFailedUpdateException
     */
    public function update(int $id, AkunRekening $akunRekening): AkunRekening;

    /**
     * @param int $id
     * @return bool
     * @throws AkunRekeningNotFoundException
     */
    public function delete(int $id): bool;
}
