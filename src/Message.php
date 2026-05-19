<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;

use function array_filter;

final class Message implements JsonSerializable
{
    /**
     * The identifier of the message to display to the customer.
     */
    private string $messageIdentifier;

    public function __construct(string $messageIdentifier)
    {
        $this->messageIdentifier = $messageIdentifier;
    }

    /**
     * Returns the identifier of the message to display to the customer.
     */
    public function getMessageIdentifier(): string
    {
        return $this->messageIdentifier;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
