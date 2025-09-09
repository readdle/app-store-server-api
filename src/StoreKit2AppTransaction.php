<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Exception\StoreKit2AppTransactionException;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Util\ArrayTypeCaseGenerator;
use Readdle\AppStoreServerAPI\Util\JWT;

final class StoreKit2AppTransaction
{
    /**
     * The app version that the app transaction applies to.
     */
    private string $applicationVersion;

    /**
     * The unique identifier of the app download transaction.
     */
    private int $appTransactionId;

    /**
     * The bundle identifier that the app transaction applies to.
     */
    private string $bundleId;

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
     * The date the app transaction was created.
     */
    private int $receiptCreationDate;

    /**
     * The server environment that signs the app transaction.
     */
    private string $receiptType;

    /**
     * ?
     */
    private int $requestDate;

    /**
     * The app version that the customer originally purchased from the App Store.
     */
    private string $originalApplicationVersion;

    /**
     * The platform on which the customer originally purchased the app.
     */
    private string $originalPlatform;

    /**
     * The date the customer originally purchased the app from the App Store.
     */
    private int $originalPurchaseDate;

    /**
     * Returns the app version that the app transaction applies to.
     *
     * This value is a machine-readable string composed of one to three period-separated integers, such as 10.4.1.
     */
    public function getApplicationVersion(): string
    {
        return $this->applicationVersion;
    }

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
     * Returns the date the app transaction was created.
     */
    public function getReceiptCreationDate(): int
    {
        return $this->receiptCreationDate;
    }

    /**
     * Returns the server environment that signs the app transaction.
     *
     * Possible values:
     * - `Sandbox`
     * - `Production`
     */
    public function getReceiptType(): string
    {
        return $this->receiptType;
    }

    /**
     * Returns ?
     */
    public function getRequestDate(): int
    {
        return $this->requestDate;
    }

    /**
     * Returns the app version that the customer originally purchased from the App Store.
     *
     * Use this value to determine which app version the customer first purchased or downloaded.
     * This value is comparable to the `$applicationVersion` value.
     *
     * The `$originalAppVersion` remains constant and doesn’t change when the customer upgrades the app.
     * The string value contains the original value of the `CFBundleShortVersionString` for apps running in macOS,
     * and the original value of the `CFBundleVersion` for apps running on all other platforms.
     *
     * In the sandbox testing environment, the `$originalAppVersion` value is always `1.0`.
     */
    public function getOriginalApplicationVersion(): string
    {
        return $this->originalApplicationVersion;
    }

    /**
     * Returns the platform on which the customer originally purchased the app.
     *
     * Possible values:
     * - `iOS`
     * - `macOS`
     * - `tvOS`
     * - `visionOS`
     */
    public function getOriginalPlatform(): string
    {
        return $this->originalPlatform;
    }

    /**
     * Returns the date the customer originally purchased the app from the App Store.
     *
     * The original purchase date remains the same, even if the customer deletes and re-installs the app.
     *
     * In the sandbox testing environment, the original purchase date is always `2013-08-01 12 AM PDT`,
     * which is `1375340400000` milliseconds in UNIX epoch time.
     */
    public function getOriginalPurchaseDate(): int
    {
        return $this->originalPurchaseDate;
    }

    /**
     * Creates an instance of the AppTransaction class from a JSON Web Token (JWT) string.
     *
     * @throws StoreKit2AppTransactionException
     */
    public static function createFromJwt(string $jwt, ?string $rootCertificate = null): self
    {
        try {
            $payload = JWT::parse($jwt, $rootCertificate);
        } catch (MalformedJWTException $e) {
            throw new StoreKit2AppTransactionException($e->getMessage());
        }

        $self = new self();

        $typeCaster = (new ArrayTypeCaseGenerator())($payload, [
            'int' => ['appTransactionId', 'receiptCreationDate', 'requestDate', 'originalPurchaseDate'],
            'string' => [
                'applicationVersion', 'bundleId', 'deviceVerification', 'deviceVerificationNonce',
                'receiptType', 'originalApplicationVersion', 'originalPlatform',
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
