<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;
use Readdle\AppStoreServerAPI\Util\ArrayTypeCaseGenerator;

use function array_key_exists;

final class AppMetadata implements JsonSerializable
{
    /**
     * The unique identifier of the app that the notification applies to.
     *
     * This property is available for apps that are downloaded from the App Store; it isn't present in the sandbox
     * environment.
     */
    private ?string $appAppleId = null;

    /**
     * The bundle identifier of the app.
     */
    private ?string $bundleId = null;

    /**
     * The version of the build that identifies an iteration of the bundle.
     */
    private ?string $bundleVersion = null;

    /**
     * The server environment that the notification applies to, either sandbox or production.
     */
    private string $environment;

    /**
     * Subscription renewal information.
     */
    private ?RenewalInfo $renewalInfo = null;

    /**
     * Transaction information.
     */
    private ?TransactionInfo $transactionInfo = null;

    /**
     * The status of an auto-renewable subscription as of the signedDate in the ResponseBodyV2.
     *
     * This field appears only for notifications sent for auto-renewable subscriptions.
     */
    private ?int $status = null;

    private function __construct()
    {
        // just a stub which prevents this class from direct instantiation
    }

    /**
     * @param array<string, mixed> $rawData
     */
    public static function createFromRawData(array $rawData): self
    {
        $appMetadata = new self();
        $typeCaster = (new ArrayTypeCaseGenerator())($rawData, [
            'int' => ['?status'],
            'string' => ['?appAppleId', '?bundleId', '?bundleVersion', 'environment'],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $appMetadata->$prop = $value;
        }

        if (array_key_exists('renewalInfo', $rawData) && $rawData['renewalInfo'] instanceof RenewalInfo) {
            $appMetadata->renewalInfo = $rawData['renewalInfo'];
        }

        if (array_key_exists('transactionInfo', $rawData) && $rawData['transactionInfo'] instanceof TransactionInfo) {
            $appMetadata->transactionInfo = $rawData['transactionInfo'];
        }

        return $appMetadata;
    }

    /**
     * Returns the unique identifier of the app that the notification applies to.
     * This property is available for apps that are downloaded from the App Store; it isn't present in the sandbox
     * environment.
     */
    public function getAppAppleId(): ?string
    {
        return $this->appAppleId;
    }

    /**
     * Returns the bundle identifier of the app.
     */
    public function getBundleId(): ?string
    {
        return $this->bundleId;
    }

    /**
     * Returns the version of the build that identifies an iteration of the bundle.
     */
    public function getBundleVersion(): ?string
    {
        return $this->bundleVersion;
    }

    /**
     * Returns the server environment that the notification applies to, either sandbox or production.
     *
     * @return Environment::PRODUCTION|Environment::SANDBOX
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Returns subscription renewal information (if any).
     */
    public function getRenewalInfo(): ?RenewalInfo
    {
        return $this->renewalInfo;
    }

    /**
     * Returns transaction information (if any).
     */
    public function getTransactionInfo(): ?TransactionInfo
    {
        return $this->transactionInfo;
    }

    /**
     * Returns the status of an auto-renewable subscription as of the signedDate in the ResponseBodyV2.
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
