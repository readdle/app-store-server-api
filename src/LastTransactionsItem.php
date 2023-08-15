<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Util\JWT;

final class LastTransactionsItem implements JsonSerializable
{
    /**
     * The auto-renewable subscription is active
     */
    const STATUS__ACTIVE = 1;

    /**
     * The auto-renewable subscription is expired
     */
    const STATUS__EXPIRED = 2;

    /**
     * The auto-renewable subscription is in a billing retry period
     */
    const STATUS__IN_BILLING_RETRY_PERIOD = 3;

    /**
     * The auto-renewable subscription is in a Billing Grace Period
     */
    const STATUS__IN_BILLING_GRACE_PERIOD = 4;

    /**
     * The auto-renewable subscription is revoked
     */
    const STATUS__REVOKED = 5;

    /**
     * The original transaction identifier of the auto-renewable subscription.
     */
    private string $originalTransactionId;

    /**
     * The status of the auto-renewable subscription.
     */
    private int $status;

    /**
     * The subscription renewal information.
     */
    private RenewalInfo $renewalInfo;

    /**
     * The transaction information.
     */
    private TransactionInfo $transactionInfo;

    private function __construct(
        string $originalTransactionId,
        int $status,
        RenewalInfo $renewalInfo,
        TransactionInfo $transactionInfo
    ) {
        $this->originalTransactionId = $originalTransactionId;
        $this->status = $status;
        $this->renewalInfo = $renewalInfo;
        $this->transactionInfo = $transactionInfo;
    }

    /**
     * @param array<string, int|string> $rawItem
     *
     * @throws MalformedJWTException
     */
    public static function createFromRawItem(array $rawItem): self
    {
        $renewalInfo = RenewalInfo::createFromRawRenewalInfo(JWT::parse($rawItem['signedRenewalInfo']));
        $transactionInfo = TransactionInfo::createFromRawTransactionInfo(JWT::parse($rawItem['signedTransactionInfo']));
        return new self($rawItem['originalTransactionId'], $rawItem['status'], $renewalInfo, $transactionInfo);
    }

    /**
     * Returns the original transaction identifier of the auto-renewable subscription.
     */
    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    /**
     * Returns the status of the auto-renewable subscription.
     *
     * @return self::STATUS__*
     */
    public function getStatus(): int
    {
        /** @phpstan-ignore-next-line */
        return $this->status;
    }

    /**
     * Returns the subscription renewal information.
     */
    public function getRenewalInfo(): RenewalInfo
    {
        return $this->renewalInfo;
    }

    /**
     * Returns the transaction information.
     */
    public function getTransactionInfo(): TransactionInfo
    {
        return $this->transactionInfo;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
