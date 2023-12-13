<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static ExtendRenewalDateResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class ExtendRenewalDateResponse extends AbstractResponse
{
    /**
     * The new subscription expiration date of a successful subscription-renewal-date extension.
     */
    protected int $effectiveDate;

    /**
     * The original transaction identifier of the subscription that experienced a service interruption.
     */
    protected string $originalTransactionId;

    /**
     * A Boolean value that indicates whether the subscription-renewal-date extension succeeded.
     */
    protected bool $success;

    /**
     * A unique ID that identifies subscription-purchase events, including subscription renewals, across devices.
     */
    protected string $webOrderLineItemId;

    /**
     * Returns the new subscription expiration date of a successful subscription-renewal-date extension.
     */
    public function getEffectiveDate(): int
    {
        return $this->effectiveDate;
    }

    /**
     * Returns the original transaction identifier of the subscription that experienced a service interruption.
     */
    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    /**
     * Returns a Boolean value that indicates whether the subscription-renewal-date extension succeeded.
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Returns unique ID that identifies subscription-purchase events, including subscription renewals, across devices.
     */
    public function getWebOrderLineItemId(): string
    {
        return $this->webOrderLineItemId;
    }
}
