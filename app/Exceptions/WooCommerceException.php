<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class WooCommerceException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null,
        public readonly array $context = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode ?? 0, $previous);
    }

    public function isCritical(): bool
    {
        return in_array($this->statusCode, [401, 403, 500, 502, 503, 504], true)
            || $this->statusCode === null;
    }
}
