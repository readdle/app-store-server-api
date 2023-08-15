<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

final class HTTPRequestFailed extends AppStoreServerAPIException
{
    public function __construct(string $message, int $code = 0)
    {
        if ($code === 0) {
            parent::__construct("HTTP request failed: $message");
        } else {
            parent::__construct("HTTP request failed with status code $code. Response text is: $message");
        }
    }
}
