<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;
use Readdle\AppStoreServerAPI\Util\ArrayTypeCaseGenerator;

use function get_object_vars;

final class TransactionInfo implements JsonSerializable
{
    /**
     * The transaction belongs to a family member who benefits from the service.
     */
    public const IN_APP_OWNERSHIP_TYPE__FAMILY_SHARED = 'FAMILY_SHARED';

    /**
     * The transaction belongs to the purchaser.
     */
    public const IN_APP_OWNERSHIP_TYPE__PURCHASED = 'PURCHASED';

    /**
     * A payment mode of a product discount that indicates a free trial.
     */
    public const OFFER_DISCOUNT_TYPE__FREE_TRIAL = 'FREE_TRIAL';

    /**
     * A payment mode of a product discount that is billed over a single or multiple billing periods.
     */
    public const OFFER_DISCOUNT_TYPE__PAY_AS_YOU_GO = 'PAY_AS_YOU_GO';

    /**
     * A payment mode of a product discount that is paid up front.
     */
    public const OFFER_DISCOUNT_TYPE__PAY_UP_FRONT = 'PAY_UP_FRONT';

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
    public const OFFER_TYPE__SUBSCRIPTION = 3;

    /**
     * Apple Support refunded the transaction on behalf of the customer for other reasons; for example, an accidental
     * purchase.
     */
    public const REVOCATION_REASON__OTHER = 0;

    /**
     * Apple Support refunded the transaction on behalf of the customer due to an actual or perceived issue within your
     * app.
     */
    public const REVOCATION_REASON__ISSUE_WITHIN_APP = 1;

    /**
     * The customer initiated the purchase, which may be for any in-app purchase type: consumable, non-consumable,
     * non-renewing subscription, or auto-renewable subscription.
     */
    public const TRANSACTION_REASON__PURCHASE = 'PURCHASE';

    /**
     * The App Store server initiated the purchase transaction to renew an auto-renewable subscription.
     */
    public const TRANSACTION_REASON__RENEWAL = 'RENEWAL';

    /**
     * An auto-renewable subscription.
     */
    public const TYPE__AUTO_RENEWABLE_SUBSCRIPTION = 'Auto-Renewable Subscription';

    /**
     * A non-consumable in-app purchase.
     */
    public const TYPE__NON_CONSUMABLE = 'Non-Consumable';

    /**
     * A consumable in-app purchase.
     */
    public const TYPE__CONSUMABLE = 'Consumable';

    /**
     * A non-renewing subscription.
     */
    public const TYPE__NON_RENEWING_SUBSCRIPTION = 'Non-Renewing Subscription';

    /**
     * A UUID that associates the transaction with a user on your own service.
     * If the app doesn't provide an appAccountToken, this string is empty.
     */
    private ?string $appAccountToken = null;

    /**
     * The bundle identifier of the app.
     */
    private string $bundleId;

    /**
     * The three-letter ISO 4217 currency code associated with the price parameter. This value is present only if price is
     * present.
     */
    private ?string $currency = null;

    /**
     * The server environment, either sandbox or production.
     */
    private string $environment;

    /**
     * The UNIX time, in milliseconds, the subscription expires or renews.
     */
    private ?int $expiresDate = null;

    /**
     * A string that describes whether the transaction was purchased by the user, or is available to them through Family
     * Sharing.
     */
    private string $inAppOwnershipType;

    /**
     * A Boolean value that indicates whether the user upgraded to another subscription.
     */
    private ?bool $isUpgraded = null;

    /**
     * The payment mode you configure for the subscription offer, such as Free Trial, Pay As You Go, or Pay Up Front.
     */
    private ?string $offerDiscountType = null;

    /**
     * The identifier that contains the promo code or the promotional offer identifier.
     * NOTE: This field applies only when the offerType is either promotional offer or subscription offer code.
     */
    private ?string $offerIdentifier = null;

    /**
     * A value that represents the promotional offer type.
     */
    private ?int $offerType = null;

    /**
     * The UNIX time, in milliseconds, that represents the purchase date of the original transaction identifier.
     */
    private int $originalPurchaseDate;

    /**
     * The transaction identifier of the original purchase.
     */
    private string $originalTransactionId;

    /**
     * An integer value that represents the price multiplied by 1000 of the in-app purchase or subscription offer you
     * configured in App Store Connect and that the system records at the time of the purchase. The currency parameter
     * indicates the currency of this price.
     */
    private ?int $price = null;

    /**
     * The product identifier of the in-app purchase.
     */
    private string $productId;

    /**
     * The UNIX time, in milliseconds, that the App Store charged the user's account for a purchase,
     * restored product, subscription, or subscription renewal after a lapse.
     */
    private int $purchaseDate;

    /**
     * The number of consumable products the user purchased.
     */
    private int $quantity;

    /**
     * The UNIX time, in milliseconds, that the App Store refunded the transaction or revoked it from Family Sharing.
     */
    private ?int $revocationDate = null;

    /**
     * The reason that the App Store refunded the transaction or revoked it from Family Sharing.
     */
    private ?int $revocationReason = null;

    /**
     * The UNIX time, in milliseconds, that the App Store signed the JSON Web Signature (JWS) data.
     */
    private int $signedDate;

    /**
     * The three-letter code that represents the country or region associated with the App Store storefront for
     * the purchase.
     *
     * This property uses the ISO 3166-1 alpha-3 country code representation.
     * This property is the same as the countryCode in StoreKit.
     */
    private ?string $storefront = null;

    /**
     * An Apple-defined value that uniquely identifies the App Store storefront associated with the purchase.
     *
     * This value is the same as the id value in StoreKit.
     */
    private ?string $storefrontId = null;

    /**
     * The identifier of the subscription group the subscription belongs to.
     */
    private ?string $subscriptionGroupIdentifier = null;

    /**
     * The unique identifier of the transaction.
     */
    private string $transactionId;

    /**
     * The reason for the purchase transaction, which indicates whether it’s a customer’s purchase or a renewal for
     * an auto-renewable subscription that the system initiates.
     */
    private ?string $transactionReason = null;

    /**
     * The type of the in-app purchase.
     */
    private string $type;

    /**
     * The unique identifier of subscription purchase events across devices, including subscription renewals.
     */
    private ?string $webOrderLineItemId = null;

    private function __construct()
    {
        // just a stub which prevents this class from direct instantiation
    }

    /**
     * @param array<string, mixed> $rawTransactionInfo
     */
    public static function createFromRawTransactionInfo(array $rawTransactionInfo): self
    {
        $transactionInfo = new self();

        $typeCaster = (new ArrayTypeCaseGenerator())($rawTransactionInfo, [
            'int' => [
                '?expiresDate', '?offerType', 'originalPurchaseDate', '?price', 'purchaseDate',
                'quantity', '?revocationDate', '?revocationReason', 'signedDate',
            ],
            'bool' => [
                '?isUpgraded',
            ],
            'string' => [
                '?appAccountToken', 'bundleId', '?currency', 'environment', 'inAppOwnershipType',
                '?offerDiscountType', '?offerIdentifier', 'originalTransactionId', 'productId', '?storefront',
                '?storefrontId', '?subscriptionGroupIdentifier', 'transactionId', '?transactionReason',
                'type', '?webOrderLineItemId',
            ],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $transactionInfo->$prop = $value;
        }

        return $transactionInfo;
    }

    /**
     * Returns a UUID that associates the transaction with a user on your own service.
     * If the app doesn't provide an appAccountToken, this string is empty.
     */
    public function getAppAccountToken(): ?string
    {
        return $this->appAccountToken;
    }

    /**
     * Returns the bundle identifier of the app.
     */
    public function getBundleId(): string
    {
        return $this->bundleId;
    }

    /**
     * Returns the three-letter ISO 4217 currency code for the price of the product.
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
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
     * Returns the UNIX time, in milliseconds, the subscription expires or renews (if any).
     */
    public function getExpiresDate(): ?int
    {
        return $this->expiresDate;
    }

    /**
     * Returns a string that describes whether the transaction was purchased by the user, or is available to them
     * through Family Sharing.
     *
     * @return self::IN_APP_OWNERSHIP_TYPE__*
     */
    public function getInAppOwnershipType(): string
    {
        return $this->inAppOwnershipType;
    }

    /**
     * Returns a Boolean value that indicates whether the user upgraded to another subscription.
     */
    public function getIsUpgraded(): ?bool
    {
        return $this->isUpgraded;
    }

    /**
     * Returns the identifier that contains the promo code or the promotional offer identifier.
     *
     * NOTE: This field applies only when the offerType is either promotional offer or subscription offer code.
     */
    public function getOfferIdentifier(): ?string
    {
        return $this->offerIdentifier;
    }

    /**
     * Returns a value that represents the offer discount type (if any).
     *
     * @return null|self::OFFER_DISCOUNT_TYPE__*
     */
    public function getOfferDiscountType(): ?string
    {
        /** @phpstan-ignore-next-line */
        return $this->offerDiscountType;
    }

    /**
     * Returns a value that represents the promotional offer type (if any).
     *
     * @return null|self::OFFER_TYPE__*
     */
    public function getOfferType(): ?int
    {
        /** @phpstan-ignore-next-line */
        return $this->offerType;
    }

    /**
     * Returns the UNIX time, in milliseconds, that represents the purchase date of the original transaction identifier.
     */
    public function getOriginalPurchaseDate(): int
    {
        return $this->originalPurchaseDate;
    }

    /**
     * Returns the transaction identifier of the original purchase.
     */
    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    /**
     * Returns the price multiplied by 1000 of the in-app purchase or subscription offer that you configured in App Store
     * Connect, as an integer.
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * Returns the product identifier of the in-app purchase.
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * Returns the UNIX time, in milliseconds, that the App Store charged the user's account for a purchase,
     * restored product, subscription, or subscription renewal after a lapse.
     */
    public function getPurchaseDate(): int
    {
        return $this->purchaseDate;
    }

    /**
     * Returns the number of consumable products the user purchased.
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Returns the UNIX time, in milliseconds, that the App Store refunded the transaction or revoked it from
     * Family Sharing (if any).
     */
    public function getRevocationDate(): ?int
    {
        return $this->revocationDate;
    }

    /**
     * The reason that the App Store refunded the transaction or revoked it from Family Sharing (if any).
     *
     * @return null|self::REVOCATION_REASON__*
     */
    public function getRevocationReason(): ?int
    {
        /** @phpstan-ignore-next-line */
        return $this->revocationReason;
    }

    /**
     * Returns the UNIX time, in milliseconds, that the App Store signed the JSON Web Signature (JWS) data.
     */
    public function getSignedDate(): int
    {
        return $this->signedDate;
    }

    /**
     * Returns the three-letter code that represents the country or region associated with the App Store storefront for
     * the purchase.
     */
    public function getStorefront(): ?string
    {
        return $this->storefront;
    }

    /**
     * Returns an Apple-defined value that uniquely identifies the App Store storefront associated with the purchase.
     */
    public function getStorefrontId(): ?string
    {
        return $this->storefrontId;
    }

    /**
     * Returns the identifier of the subscription group the subscription belongs to (if any).
     */
    public function getSubscriptionGroupIdentifier(): ?string
    {
        return $this->subscriptionGroupIdentifier;
    }

    /**
     * Returns the unique identifier of the transaction.
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * Returns the type of the in-app purchase.
     *
     * @return self::TRANSACTION_REASON__*
     */
    public function getTransactionReason(): ?string
    {
        return $this->transactionReason;
    }

    /**
     * Returns the type of the in-app purchase.
     *
     * @return self::TYPE__*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns the unique identifier of subscription purchase events across devices, including subscription
     * renewals (if any).
     */
    public function getWebOrderLineItemId(): ?string
    {
        return $this->webOrderLineItemId;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
