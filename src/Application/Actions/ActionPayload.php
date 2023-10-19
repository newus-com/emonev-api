<?php

declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    private int $statusCode;

    /**
     * @var array|object|null
     */
    private $data;

    /**
     * @var string|null
     */
    private $message;

    private ?array $error;

    public function __construct(
        int $statusCode = 200,
        $data = null,
        $message = null,
        ?array $error = null
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->message = $message;
        $this->error = $error;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function getError(): array
    {
        return $this->error;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $payload = [
            'status' => $this->statusCode,
        ];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } 
        if ($this->error !== null) {
            $payload['error'] = $this->error;
        }
        if ($this->message !== null) {
            $payload['message'] = $this->message;
        }
        return $payload;
    }
}
