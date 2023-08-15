<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

final class MalformedResponseException extends AppStoreServerAPIException
{
    public function __construct(string $message)
    {
        parent::__construct("Malformed response: $message");
    }
}
