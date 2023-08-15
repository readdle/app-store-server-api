<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;

final class SubscriptionGroupIdentifierItem implements JsonSerializable
{
    /**
     * The subscription group identifier of the auto-renewable subscriptions in the lastTransactions array.
     */
    private string $subscriptionGroupIdentifier;

    /**
     * An array of the most recent transaction information and renewal information for all auto-renewable
     * subscriptions in the subscription group.
     *
     * @var array<LastTransactionsItem>
     */
    private array $lastTransactions;

    /**
     * @param array<LastTransactionsItem> $lastTransactions
     */
    private function __construct(string $subscriptionGroupIdentifier, array $lastTransactions)
    {
        $this->subscriptionGroupIdentifier = $subscriptionGroupIdentifier;
        $this->lastTransactions = $lastTransactions;
    }

    /**
     * @param array<string, mixed> $rawItem
     *
     * @throws MalformedJWTException
     */
    public static function createFromRawItem(array $rawItem): self
    {
        $lastTransactions = [];

        foreach ($rawItem['lastTransactions'] as $rawTransactionItem) {
            $lastTransactions[] = LastTransactionsItem::createFromRawItem($rawTransactionItem);
        }

        return new self($rawItem['subscriptionGroupIdentifier'], $lastTransactions);
    }

    /**
     * Returns the subscription group identifier of the auto-renewable subscriptions in the lastTransactions array.
     */
    public function getSubscriptionGroupIdentifier(): string
    {
        return $this->subscriptionGroupIdentifier;
    }

    /**
     * Returns an array of the most recent transaction information and renewal information for all auto-renewable
     * subscriptions in the subscription group.
     *
     * @return array<LastTransactionsItem>
     */
    public function getLastTransactions(): array
    {
        return $this->lastTransactions;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
