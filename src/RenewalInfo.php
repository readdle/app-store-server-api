<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;
use Readdle\AppStoreServerAPI\Util\ArrayTypeCaseGenerator;

use function get_object_vars;

final class RenewalInfo implements JsonSerializable
{
    /**
     * Automatic renewal is off.
     * The customer has turned off automatic renewal for the subscription, and it won't renew at the end of the current
     * subscription period.
     */
    const AUTO_RENEW_STATUS__OFF = 0;

    /**
     * Automatic renewal is on.
     * The subscription renews at the end of the current subscription period.
     */
    const AUTO_RENEW_STATUS__ON = 1;

    /**
     * The customer canceled their subscription.
     */
    public const EXPIRATION_INTENT__CANCEL = 1;

    /**
     * Billing error; for example, the customer's payment information is no longer valid.
     */
    public const EXPIRATION_INTENT__BILLING_ERROR = 2;

    /**
     * The customer didn't consent to an auto-renewable subscription price increase
     * that requires customer consent, allowing the subscription to expire.
     */
    public const EXPIRATION_INTENT__PRICE_INCREASE = 3;

    /**
     * The product wasn't available for purchase at the time of renewal.
     */
    public const EXPIRATION_INTENT__UNAVAILABLE_PRODUCT = 4;

    /**
     * An introductory offer.
     */
    public const OFFER_TYPE__INTRODUCTORY = 1;

    /**
     * A promotional offer.
     */
    public const OFFER_TYPE__PROMOTIONAL = 2;

    /**
     * An offer with a subscription offer code.
     */
    public const OFFER_TYPE__SUBSCRIPTION_OFFER_CODE = 3;

    /**
     * A win-back offer.
     */
    public const OFFER_TYPE__WIN_BACK = 4;

    /**
     * The customer hasn't yet responded to an auto-renewable subscription price increase that requires customer
     * consent.
     */
    public const PRICE_INCREASE_STATUS__NOT_RESPONDED = 0;

    /**
     * The customer consented to an auto-renewable subscription price increase that requires customer consent,
     * or the App Store has notified the customer of an auto-renewable subscription price increase that doesn't require
     * consent.
     */
    public const PRICE_INCREASE_STATUS__CONSENTED = 1;

    /**
     * The UUID that an app optionally generates to map a customer’s In-App Purchase with its resulting App Store
     * transaction.
     */
    private ?string $appAccountToken = null;

    /**
     * The unique identifier of the app download transaction.
     */
    private ?string $appTransactionId = null;

    /**
     * The product identifier of the product that renews at the next billing period.
     */
    private string $autoRenewProductId;

    /**
     * The renewal status for an auto-renewable subscription.
     */
    private int $autoRenewStatus;

    /**
     * The three-letter ISO 4217 currency code for the price of the product.
     */
    private ?string $currency = null;

    /**
     * An array of win-back offer identifiers that a customer is eligible to redeem,
     * which sorts the identifiers with the best offers first.
     *
     * @var string[]
     */
    private ?array $eligibleWinBackOfferIds = null;

    /**
     * The server environment, either sandbox or production.
     */
    private string $environment;

    /**
     * The reason a subscription expired.
     */
    private ?int $expirationIntent = null;

    /**
     * The time when the billing grace period for subscription renewals expires.
     */
    private ?int $gracePeriodExpiresDate = null;

    /**
     * The Boolean value that indicates whether the App Store is attempting to automatically renew an expired
     * subscription.
     */
    private ?bool $isInBillingRetryPeriod = null;

    /**
     * The payment mode for subscription offers on an auto-renewable subscription.
     */
    private ?string $offerDiscountType = null;

    /**
     * The offer code or the promotional offer identifier.
     */
    private ?string $offerIdentifier = null;

    /**
     * The duration of the offer.
     *
     * This field is in ISO 8601 duration format.
     * The following list shows examples of offer period values.
     *
     * - Value: P1M
     *   - Single period length: 1 month
     *   - Period count: 1
     *
     * - Value: P2M
     *   - Single period length: 1 month
     *   - Period count: 2
     *
     * - Value: P3D
     *   - Single period length: 3 days
     *   - Period count: 1
     */
    private ?string $offerPeriod = null;

    /**
     * The type of subscription offer.
     */
    private ?int $offerType = null;

    /**
     * The original transaction identifier of a purchase.
     */
    private string $originalTransactionId;

    /**
     * The status that indicates whether the auto-renewable subscription is subject to a price increase.
     */
    private ?int $priceIncreaseStatus = null;

    /**
     * The product identifier of the in-app purchase.
     */
    private string $productId;

    /**
     * The earliest start date of an auto-renewable subscription in a series of subscription purchases
     * that ignores all lapses of paid service that are 60 days or less.
     */
    private int $recentSubscriptionStartDate;

    /**
     * The UNIX time, in milliseconds, that the most recent auto-renewable subscription purchase expires.
     *
     * The renewalDate is a value that’s always present in the payload for auto-renewable subscriptions,
     * even for expired subscriptions. This date indicates the expiration date of the most recent auto-renewable
     * subscription purchase, including renewals, and may be in the past. For subscriptions that renew successfully,
     * the renewalDate is the date when the subscription renews.
     */
    private ?int $renewalDate = null;

    /**
     * The renewal price, in milli-units, of the auto-renewable subscription that renews at the next billing period.
     */
    private ?int $renewalPrice = null;

    /**
     * The UNIX time, in milliseconds, that the App Store signed the JSON Web Signature data.
     */
    private int $signedDate;

    private function __construct()
    {
        // just a stub which prevents this class from direct instantiation
    }

    /**
     * @param array<string, mixed> $rawRenewalInfo
     */
    public static function createFromRawRenewalInfo(array $rawRenewalInfo): self
    {
        $renewalInfo = new self();
        $typeCaster = (new ArrayTypeCaseGenerator())($rawRenewalInfo, [
            'int' => [
                'autoRenewStatus', '?expirationIntent', '?gracePeriodExpiresDate', '?offerType',
                '?priceIncreaseStatus', 'recentSubscriptionStartDate', '?renewalDate', '?renewalPrice', 'signedDate',
            ],
            'bool' => [
                '?isInBillingRetryPeriod',
            ],
            'string' => [
                '?appAccountToken', '?appTransactionId', 'autoRenewProductId', '?currency', 'environment',
                '?offerDiscountType', '?offerIdentifier', '?offerPeriod', 'originalTransactionId', 'productId',
            ],
            'array' => [
                '?eligibleWinBackOfferIds'
            ],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $renewalInfo->$prop = $value;
        }

        return $renewalInfo;
    }

    /**
     * Returns the UUID that an app optionally generates to map a customer’s In-App Purchase with its resulting App Store
     * transaction.
     */
    public function getAppAccountToken(): ?string
    {
        return $this->appAccountToken;
    }

    /**
     * Returns the unique identifier of the app download transaction.
     */
    public function getAppTransactionId(): ?string
    {
        return $this->appTransactionId;
    }

    /**
     * Returns the product identifier of the product that renews at the next billing period.
     */
    public function getAutoRenewProductId(): string
    {
        return $this->autoRenewProductId;
    }

    /**
     * Returns the renewal status for an auto-renewable subscription.
     *
     * @return self::AUTO_RENEW_STATUS__*
     */
    public function getAutoRenewStatus(): int
    {
        /** @phpstan-ignore-next-line */
        return $this->autoRenewStatus;
    }

    /**
     * Returns the three-letter ISO 4217 currency code for the price of the product.
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Returns an array of win-back offer identifiers that a customer is eligible to redeem,
     * which sorts the identifiers with the best offers first.
     *
     * @return string[]
     */
    public function getEligibleWinBackOfferIds(): ?array
    {
        return $this->eligibleWinBackOfferIds;
    }

    /**
     * Returns the server environment, either sandbox or production.
     *
     * @return Environment::PRODUCTION|Environment::SANDBOX
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Returns the reason a subscription expired (if any).
     *
     * @return null|self::EXPIRATION_INTENT__*
     */
    public function getExpirationIntent(): ?int
    {
        /** @phpstan-ignore-next-line */
        return $this->expirationIntent;
    }

    /**
     * Returns the time when the billing grace period for subscription renewals expires (if any).
     */
    public function getGracePeriodExpiresDate(): ?int
    {
        return $this->gracePeriodExpiresDate;
    }

    /**
     * Returns the Boolean value that indicates whether the App Store is attempting to automatically renew an expired
     * subscription.
     */
    public function getIsInBillingRetryPeriod(): ?bool
    {
        return $this->isInBillingRetryPeriod;
    }

    /**
     * Returns the payment mode for subscription offers on an auto-renewable subscription.
     */
    public function getOfferDiscountType(): ?string
    {
        return $this->offerDiscountType;
    }

    /**
     * Returns the offer code or the promotional offer identifier (if any).
     */
    public function getOfferIdentifier(): ?string
    {
        return $this->offerIdentifier;
    }

    /**
     * Returns the duration of the offer.
     *
     */
    public function getOfferPeriod(): ?string
    {
        return $this->offerPeriod;
    }

    /**
     * Returns the type of subscription offer (if any).
     *
     * @return self::OFFER_TYPE__*
     */
    public function getOfferType(): ?int
    {
        /** @phpstan-ignore-next-line */
        return $this->offerType;
    }

    /**
     * Returns the original transaction identifier of a purchase.
     */
    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    /**
     * Returns the status that indicates whether the auto-renewable subscription is subject to a price increase (if any)
     *
     * @return self::PRICE_INCREASE_STATUS__*
     */
    public function getPriceIncreaseStatus(): ?int
    {
        /** @phpstan-ignore-next-line */
        return $this->priceIncreaseStatus;
    }

    /**
     * Returns the product identifier of the in-app purchase.
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * Returns the earliest start date of an auto-renewable subscription in a series of subscription purchases
     * that ignores all lapses of paid service that are 60 days or less.
     */
    public function getRecentSubscriptionStartDate(): int
    {
        return $this->recentSubscriptionStartDate;
    }

    /**
     * Returns the UNIX time, in milliseconds, that the most recent auto-renewable subscription purchase expires.
     */
    public function getRenewalDate(): ?int
    {
        return $this->renewalDate;
    }

    /**
     * Returns the renewal price, in milli-units, of the auto-renewable subscription that renews at the next billing
     * period.
     */
    public function getRenewalPrice(): ?int
    {
        return $this->renewalPrice;
    }

    /**
     * Returns the UNIX time, in milliseconds, that the App Store signed the JSON Web Signature data.
     */
    public function getSignedDate(): int
    {
        return $this->signedDate;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
