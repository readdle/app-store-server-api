<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

final class MalformedJWTException extends AppStoreServerAPIException
{
    public function __construct(string $message)
    {
        parent::__construct("JWT is malformed: $message");
    }
}
