<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

use Throwable;

final class JWTCreationException extends AppStoreServerAPIException
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct("JWT creation error: $message", 0, $previous);
    }
}
