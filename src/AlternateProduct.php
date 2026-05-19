<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;

use function array_filter;

final class AlternateProduct implements JsonSerializable
{
    /**
     * The customer pays the full price of the subscription billing period up front.
     */
    const BILLING_PLAN_TYPE__BILLED_UPFRONT = 'BILLED_UPFRONT';

    /**
     * The customer pays monthly for a subscription with a yearly commitment.
     */
    const BILLING_PLAN_TYPE__MONTHLY = 'MONTHLY';

    /**
     * The message identifier of the text to display in the switch-plan retention message.
     */
    private string $messageIdentifier;

    /**
     * The product identifier of the subscription the retention message suggests for your customer to switch to.
     */
    private string $productId;

    /**
     * The billing plan type of the subscription the retention message suggests.
     */
    private ?string $billingPlanType;

    public function __construct(string $messageIdentifier, string $productId, ?string $billingPlanType = null)
    {
        $this->messageIdentifier = $messageIdentifier;
        $this->productId = $productId;
        $this->billingPlanType = $billingPlanType;
    }

    /**
     * Returns the message identifier of the text to display in the switch-plan retention message.
     */
    public function getMessageIdentifier(): string
    {
        return $this->messageIdentifier;
    }

    /**
     * Returns the product identifier of the subscription the retention message suggests for your customer to switch to.
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * Returns the billing plan type of the subscription the retention message suggests.
     *
     * @return self::BILLING_PLAN_TYPE__*|null
     */
    public function getBillingPlanType(): ?string
    {
        return $this->billingPlanType;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), static fn ($value) => $value !== null);
    }
}
