<?php

declare(strict_types=1);

namespace App\Domain\Perencanaan\Program;

use App\Domain\Perencanaan\Program\Program;
use App\Domain\Dinas\ProgramNotFoundException;
use App\Domain\Program\ProgramFailedInsertException;
use App\Domain\Program\ProgramFailedUpdateException;
use App\Domain\Perencanaan\Bidang\BidangNotFoundException;

interface ProgramRepository
{
    /**
     * @param array|null $options
     * @return Program[]
     */
    public function findAll(array|null $options): array;


    /**
     * @param int $id
     * @return Program
     * @throws ProgramNotFoundException
     */
    public function findOneById(int $id): Program;

    /**
     * @param Program $program
     * @return Program
     * @throws ProgramFailedInsertException
     * @throws BidangNotFoundException
     */
    public function create(Program $program): Program;

    /**
     * @param int $id
     * @param Program $program
     * @return Program
     * @throws ProgramNotFoundException
     * @throws ProgramFailedUpdateException
     * @throws BidangNotFoundException
     */
    public function update(int $id, Program $program): Program;

    /**
     * @param int $id
     * @return bool
     * @throws ProgramNotFoundException
     */
    public function delete(int $id): bool;
}
