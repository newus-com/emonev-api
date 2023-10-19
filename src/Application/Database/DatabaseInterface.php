<?php

declare(strict_types=1);

namespace App\Application\Database;

interface DatabaseInterface
{
    /**
     * @return mixed
     */
    public function h();

    /**
     * @return mixed
     */
    public function c();
}
