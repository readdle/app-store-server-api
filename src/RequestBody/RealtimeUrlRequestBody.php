<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

final class RealtimeUrlRequestBody extends AbstractRequestBody
{
    /**
     * A string that contains the URL of your Get Retention Message endpoint for configuration.
     * Maximum length: 256
     */
    protected string $realtimeURL;

    protected array $requiredFields = ['realtimeURL'];
}
