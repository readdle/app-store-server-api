<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

use Throwable;

final class HTTPRequestAborted extends AppStoreServerAPIException
{
    public function __construct(string $message, Throwable $previous)
    {
        parent::__construct("HTTP request aborted: $message", 0, $previous);
    }
}
