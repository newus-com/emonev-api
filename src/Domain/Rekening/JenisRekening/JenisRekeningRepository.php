<?php

declare(strict_types=1);

namespace App\Domain\Rekening\JenisRekening;

use App\Domain\Dinas\JenisRekeningNotFoundException;
use App\Domain\JenisRekening\JenisRekeningFailedInsertException;
use App\Domain\JenisRekening\JenisRekeningFailedUpdateException;
use App\Domain\Rekening\KelompokRekening\KelompokRekeningNotFoundException;

interface JenisRekeningRepository
{
    /**
     * @param array|null $options
     * @return JenisRekening[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return JenisRekening
     * @throws JenisRekeningNotFoundException
     */
    public function findOneById(int $id): JenisRekening;

    /**
     * @param JenisRekening $JenisRekenig
     * @return JenisRekening
     * @throws JenisRekeningFailedInsertException
     * @throws KelompokRekeningNotFoundException
     */
    public function create(JenisRekening $JenisRekenig): JenisRekening;

    /**
     * @param int $id
     * @param JenisRekening $JenisRekenig
     * @return JenisRekening
     * @throws JenisRekeningNotFoundException
     * @throws JenisRekeningFailedUpdateException
     * @throws KelompokRekeningNotFoundException
     */
    public function update(int $id, JenisRekening $JenisRekenig): JenisRekening;

    /**
     * @param int $id
     * @return bool
     * @throws JenisRekeningNotFoundException
     */
    public function delete(int $id): bool;
}
