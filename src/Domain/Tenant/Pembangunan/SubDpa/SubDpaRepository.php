<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Pembangunan\SubDpa;

use App\Domain\Tenant\Pembangunan\SubDpa\SubDpa;

interface SubDpaRepository
{
    public function findAll(array|null $options): array;

    public function findOneById(int $id);

    public function create(array $subDpa);

    public function update(int $id, array $SubDpa);

    public function delete(int $id): bool;
}
