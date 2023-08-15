<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

final class Environment
{
    /**
     * Indicates that the data/notification applies to the production environment.
     *
     * @var string
     */
    const PRODUCTION = 'Production';

    /**
     * Indicates that the data/notification applies to testing in the sandbox environment.
     *
     * @var string
     */
    const SANDBOX = 'Sandbox';
}
