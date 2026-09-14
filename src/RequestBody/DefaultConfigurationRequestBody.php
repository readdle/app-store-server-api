<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

final class DefaultConfigurationRequestBody extends AbstractRequestBody
{
    /**
     * The message identifier of the message to configure as a default message.
     */
    protected string $messageIdentifier;

    protected array $requiredFields = ['messageIdentifier'];
}
