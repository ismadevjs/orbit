<?php

namespace App\Services\CloudflareTurnstile;

use JsonSerializable;

final readonly class CloudflareTurnstileResponse implements JsonSerializable
{
    public function __construct(
        private bool  $success,
        private array $errorCodes,
    )
    {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }

    public function jsonSerialize(): array
    {
        return [
            'success'     => $this->success,
            'error-codes' => $this->errorCodes,
        ];
    }
}