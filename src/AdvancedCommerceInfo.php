<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;

final class AdvancedCommerceInfo implements JsonSerializable
{
    /**
     * The identifier of the message to display to the customer, along with the offer or switch-plan recommendation
     * provided in advancedCommerceData.
     */
    private string $messageIdentifier;

    /**
     * A Base64-encoded JSON object which contains a JWS describing an offer or switch-plan recommendation.
     */
    private string $advancedCommerceData;

    public function __construct(string $messageIdentifier, string $advancedCommerceData)
    {
        $this->messageIdentifier = $messageIdentifier;
        $this->advancedCommerceData = $advancedCommerceData;
    }

    /**
     * Returns the identifier of the message to display to the customer.
     */
    public function getMessageIdentifier(): string
    {
        return $this->messageIdentifier;
    }

    /**
     * Returns the Base64-encoded JSON object which contains a JWS describing an offer or switch-plan recommendation.
     */
    public function getAdvancedCommerceData(): string
    {
        return $this->advancedCommerceData;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
