<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static DefaultConfigurationResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class DefaultConfigurationResponse extends AbstractResponse
{
    /**
     * The message identifier of the retention message you configured as a default.
     */
    protected string $messageIdentifier;

    /**
     * Returns the message identifier of the retention message you configured as a default.
     */
    public function getMessageIdentifier(): string
    {
        return $this->messageIdentifier;
    }
}
