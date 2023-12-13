<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static MassExtendRenewalDateStatusResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class MassExtendRenewalDateStatusResponse extends AbstractResponse
{
    /**
     * The UUID that represents your request for a subscription-renewal-date extension.
     */
    protected string $requestIdentifier;

    /**
     * A Boolean value that’s TRUE to indicate that the App Store completed your request to extend a subscription
     * renewal date for all eligible subscribers.
     * The value is FALSE if the request is in progress.
     */
    protected bool $complete;

    /**
     * The date that the App Store completes the request.
     * Appears only if complete is TRUE.
     */
    protected ?int $completeDate = null;

    /**
     * The final count of subscribers that fail to receive a subscription-renewal-date extension.
     * Appears only if complete is TRUE.
     */
    protected ?int $failedCount = null;

    /**
     * The final count of subscribers that successfully receive a subscription-renewal-date extension.
     * Appears only if complete is TRUE.
     */
    protected ?int $succeededCount = null;

    /**
     * Returns the UUID that represents your request for a subscription-renewal-date extension.
     */
    public function getRequestIdentifier(): string
    {
        return $this->requestIdentifier;
    }

    /**
     * Returns a Boolean value that’s `true` to indicate that the App Store completed your request to extend
     * a subscription renewal date for all eligible subscribers.
     * The value is `false` if the request is in progress.
     */
    public function isComplete(): bool
    {
        return $this->complete;
    }

    /**
     * Returns the date that the App Store completes the request.
     * Returns `null` if complete is `false`.
     */
    public function getCompleteDate(): ?int
    {
        return $this->completeDate;
    }

    /**
     * Returns the final count of subscribers that fail to receive a subscription-renewal-date extension.
     * Returns `null` if complete is `false`.
     */
    public function getFailedCount(): ?int
    {
        return $this->failedCount;
    }

    /**
     * Returns the final count of subscribers that successfully receive a subscription-renewal-date extension.
     * Returns `null` if complete is `false`.
     */
    public function getSucceededCount(): ?int
    {
        return $this->succeededCount;
    }
}
