<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

final class InvalidImplementationException extends AppStoreServerAPIException
{
    public function __construct(string $requestClass, string $responseClass)
    {
        parent::__construct("Invalid implementation of request $requestClass and/or response $responseClass");
    }
}
