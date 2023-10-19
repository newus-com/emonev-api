<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Pembangunan\Dpa;

use App\Domain\Tenant\Pembangunan\Dpa\Dpa;

interface DpaRepository
{
    public function findAll(array|null $options): array;
    public function create(Dpa $dpa);
    public function findOneById(int $id);
    public function update(int $id, Dpa $dpa);
}
