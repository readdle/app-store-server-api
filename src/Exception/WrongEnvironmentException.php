<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

use Readdle\AppStoreServerAPI\Environment;

final class WrongEnvironmentException extends AppStoreServerAPIException
{
    public function __construct(string $environment)
    {
        parent::__construct(sprintf(
            'Environment should be either Environment::PRODUCTION (%s) or Environment::SANDBOX (%s), %s is passed',
            Environment::PRODUCTION,
            Environment::SANDBOX,
            $environment
        ));
    }
}
