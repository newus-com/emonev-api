<?php

declare(strict_types=1);

namespace App\Application\Token;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token implements TokenInterface
{
    public $key;
    public $payload;

    public function __construct(string $key, array $payload)
    {
        $this->key = $key;
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function encode(int $id, ?string $type = 'access')
    {
        $payload = [];
        if($type == 'access'){
            $payload = $this->payload['access'];
        }else{
            $payload = $this->payload['refresh'];
        }
        $payload['id'] = $id;
        $jwt = JWT::encode($payload, $this->key, 'HS256');
        return $jwt;
    }

    /**
     * @return mixed
     */
    public function decode(string $jwt)
    {
        $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
        return (array)$decoded;
    }
}
