<?php

declare(strict_types=1);

namespace App\Application\Token;

interface TokenInterface
{
    /**
     * @return mixed
     */
    public function encode(int $id, ?string $type = 'access');

    /**
     * @return mixed
     */
    public function decode(string $jwt);
}
