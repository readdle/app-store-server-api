<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

final class UpdateAppAccountTokenRequestBody extends AbstractRequestBody
{
    /**
     * The UUID of the in-app user account that completed the consumable in-app purchase transaction.
     */
    protected string $appAccountToken;

    protected array $requiredFields = [
        'appAccountToken',
    ];
}
