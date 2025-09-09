<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Exception\StoreKit2TransactionException;
use Readdle\AppStoreServerAPI\Util\ArrayTypeCaseGenerator;
use Readdle\AppStoreServerAPI\Util\JWT;

final class StoreKit2Transaction
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
     * The unique identifier of the app download transaction.
     */
    private int $appTransactionId;

    /**
     * The bundle identifier that the app transaction applies to.
     */
    private string $bundleId;

    /**
     * The currency of the price of the product.
     */
    private string $currency;

    /**
     * The device verification value to use to verify whether the app transaction belongs to the device.
     */
    private string $deviceVerification;

    /**
     * The UUID used to compute the device verification value.
     *
     * For more information, see <a href="https://developer.apple.com/documentation/storekit/appstore/deviceverificationid">deviceVerificationID</a>.
     */
    private string $deviceVerificationNonce;

    /**
     * The server environment that generates and signs the transaction.
     */
    private string $environment;

    /**
     * The date the subscription expires or renews.
     */
    private int $expiresDate;

    /**
     * A value that indicates whether the transaction was purchased by the user, or is made available to them through Family Sharing.
     */
    private string $inAppOwnershipType;

    /**
     * The date the customer originally purchased the app from the App Store.
     */
    private int $originalPurchaseDate;

    /**
     * The original transaction identifier of a purchase.
     */
    private int $originalTransactionId;

    /**
     * The price of the in-app purchase that the system records in the transaction.
     */
    private int $price;

    /**
     * The product identifier of the in-app purchase.
     */
    private string $productId;

    /**
     * The date that the App Store charged the user’s account for a purchased or restored product,
     * or for a subscription purchase or renewal after a lapse.
     */
    private int $purchaseDate;

    /**
     * The number of consumable products purchased.
     */
    private int $quantity;

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
    private string $storefront;

    /**
     * An Apple-defined value that uniquely identifies the App Store storefront associated with the purchase.
     *
     * This value is the same as the id value in StoreKit.
     */
    private int $storefrontId;

    /**
     * The identifier of the subscription group the subscription belongs to.
     */
    private string $subscriptionGroupIdentifier;

    /**
     * The unique identifier of the transaction.
     */
    private int $transactionId;

    /**
     * The reason for the purchase transaction, which indicates whether it’s a customer’s purchase or a renewal for
     * an auto-renewable subscription that the system initiates.
     */
    private string $transactionReason;

    /**
     * The type of the in-app purchase.
     */
    private string $type;

    /**
     * The unique identifier of subscription purchase events across devices, including subscription renewals.
     */
    private int $webOrderLineItemId;

    /**
     * Returns the unique identifier of the app download transaction.
     *
     * The App Store generates a single, globally unique appTransactionID for each Apple Account
     * that downloads your app and for each family group member for apps that support Family Sharing.
     *
     * This value remains the same for the same Apple Account and app if the customer redownloads the
     * app on any device, receives a refund, repurchases the app, or changes the storefront. For apps
     * that support Family Sharing, the appTransactionID is unique for each family group member.
     *
     * The appTransactionID is available even if a customer makes no in-app purchases.
     */
    public function getAppTransactionId(): int
    {
        return $this->appTransactionId;
    }

    /**
     * Returns the bundle identifier that the app transaction applies to.
     */
    public function getBundleId(): string
    {
        return $this->bundleId;
    }

    /**
     * Returns the currency of the price of the product.
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Returns the device verification value to use to verify whether the app transaction belongs to the device.
     *
     * For more information, see <a href="https://developer.apple.com/documentation/storekit/appstore/deviceverificationid">deviceVerificationID</a>.
     */
    public function getDeviceVerification(): string
    {
        return $this->deviceVerification;
    }

    /**
     * Returns the UUID used to compute the device verification value.
     *
     * For more information, see <a href="https://developer.apple.com/documentation/storekit/appstore/deviceverificationid">deviceVerificationID</a>.
     */
    public function getDeviceVerificationNonce(): string
    {
        return $this->deviceVerificationNonce;
    }

    /**
     * Returns the server environment that generates and signs the transaction.
     *
     * - `Sandbox`
     * - `Production`
     *
     * Use the `Environment::*` constants to compare the environment values.
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Returns the date the subscription expires or renews.
     */
    public function getExpiresDate(): int
    {
        return $this->expiresDate;
    }

    /**
     * Returns a value that indicates whether the transaction was purchased by the user, or is made available to them through Family Sharing.
     *
     * Possible values:
     *  - `PURCHASED`
     *  - `FAMILY_SHARED`
     *
     * Use the `StoreKit2Transaction::IN_APP_OWNERSHIP_TYPE__*` constants to compare the type values.
     */
    public function getInAppOwnershipType(): string
    {
        return $this->inAppOwnershipType;
    }

    /**
     * Returns the date the customer originally purchased the app from the App Store.
     */
    public function getOriginalPurchaseDate(): int
    {
        return $this->originalPurchaseDate;
    }

    /**
     * Returns the original transaction identifier of a purchase.
     */
    public function getOriginalTransactionId(): int
    {
        return $this->originalTransactionId;
    }

    /**
     * Returns the price of the in-app purchase that the system records in the transaction.
     *
     * This value represents the price of the in-app purchase, in units of the currency, that the system records in the transaction.
     * The price value reflects all of the following:
     * - The price you configured in App Store Connect, which the system records on the purchase date (purchaseDate).
     * - The discount from a subscription offer in the offer property, if the transaction includes an offer.
     * - The purchasedQuantity of a consumable in-app purchase. The price value shows the total amount of the transaction for the quantity that the customer purchased.
     */
    public function getPrice(): int
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
     * Returns the date that the App Store charged the user’s account for a purchased or restored product,
     * or for a subscription purchase or renewal after a lapse.
     */
    public function getPurchaseDate(): int
    {
        return $this->purchaseDate;
    }

    /**
     * Returns the number of consumable products purchased.
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Returns the UNIX time, in milliseconds, that the App Store signed the JSON Web Signature (JWS) data.
     */
    public function getSignedDate(): int
    {
        return $this->signedDate;
    }

    /**
     * Returns the three-letter code that represents the country or region associated with the App Store storefront for the purchase.
     *
     * This value uses the ISO 3166-1 alpha-3 country code representation.
     * This value is the same as the countryCode in StoreKit.
     */
    public function getStorefront(): string
    {
        return $this->storefront;
    }

    /**
     * Returns an Apple-defined value that uniquely identifies the App Store storefront associated with the purchase.
     *
     * This value is the same as the id value in StoreKit.
     */
    public function getStorefrontId(): int
    {
        return $this->storefrontId;
    }

    /**
     * Returns the identifier of the subscription group the subscription belongs to.
     */
    public function getSubscriptionGroupIdentifier(): string
    {
        return $this->subscriptionGroupIdentifier;
    }

    /**
     * Returns the unique identifier of the transaction.
     */
    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    /**
     * Returns the reason for the purchase transaction, which indicates whether it’s a customer’s purchase or a renewal
     * for an auto-renewable subscription that the system initiates.
     */
    public function getTransactionReason(): string
    {
        return $this->transactionReason;
    }

    /**
     * Returns the type of the in-app purchase.
     *
     * Possible values:
     *  - `Auto-Renewable Subscription`
     *  - `Non-Consumable`
     *  - `Consumable`
     *  - `Non-Renewing Subscription`
     *
     * Use the `StoreKit2Transaction::TYPE__*` constants to compare the type values.
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getWebOrderLineItemId(): int
    {
        return $this->webOrderLineItemId;
    }

    /**
     * @throws StoreKit2TransactionException
     */
    public static function createFromJwt(string $jwt, ?string $rootCertificate = null): self
    {
        try {
            $payload = JWT::parse($jwt, $rootCertificate);
        } catch (MalformedJWTException $e) {
            throw new StoreKit2TransactionException($e->getMessage());
        }

        $self = new self();

        $typeCaster = (new ArrayTypeCaseGenerator())($payload, [
            'int' => [
                'appTransactionId', 'expiresDate', 'originalPurchaseDate', 'originalTransactionId', 'price',
                'purchaseDate', 'quantity', 'signedDate', 'storefrontId', 'transactionId', 'webOrderLineItemId',
            ],
            'string' => [
                'bundleId', 'currency', 'deviceVerification', 'deviceVerificationNonce', 'environment', 'inAppOwnershipType',
                'productId', 'storefront', 'subscriptionGroupIdentifier', 'transactionReason', 'type',
            ],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $self->$prop = $value;
        }

        return $self;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
