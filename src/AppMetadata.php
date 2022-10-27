<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Util\Helper;
use function array_key_exists;

final class AppMetadata
{
    /**
     * Indicates that the notification applies to testing in the sandbox environment.
     */
    public const ENVIRONMENT__SANDBOX = 'Sandbox';

    /**
     * Indicates that the notification applies to the production environment.
     */
    public const ENVIRONMENT__PRODUCTION = 'Production';

    /**
     * The unique identifier of the app that the notification applies to.
     * This property is available for apps that are downloaded from the App Store; it isn’t present in the sandbox environment.
     */
    private ?string $appAppleId = null;

    /**
     * The bundle identifier of the app.
     */
    private string $bundleId;

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
    private ?RenewalInfo $renewalInfo;

    /**
     * Transaction information.
     */
    private ?TransactionInfo $transactionInfo;

    private function __construct()
    {
        // just a stub which prevents this class from direct instantiation
    }

    public static function createFromPayload(array $payload): self
    {
        $appMetadata = new self();
        $typeCaster = Helper::arrayTypeCastGenerator($payload, [
            'string' => ['appAppleId', 'bundleId', 'bundleVersion', 'environment'],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $appMetadata->$prop = $value;
        }

        if (array_key_exists('renewalInfo', $payload) && $payload['renewalInfo'] instanceof RenewalInfo) {
            $appMetadata->renewalInfo = $payload['renewalInfo'];
        }

        if (array_key_exists('transactionInfo', $payload) && $payload['transactionInfo'] instanceof TransactionInfo) {
            $appMetadata->transactionInfo = $payload['transactionInfo'];
        }

        return $appMetadata;
    }

    public function getAppAppleId(): ?string
    {
        return $this->appAppleId;
    }

    public function getBundleId(): string
    {
        return $this->bundleId;
    }

    public function getBundleVersion(): ?string
    {
        return $this->bundleVersion;
    }

    /**
     * @return self::ENVIRONMENT__*
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getRenewalInfo(): ?RenewalInfo
    {
        return $this->renewalInfo;
    }

    public function getTransactionInfo(): ?TransactionInfo
    {
        return $this->transactionInfo;
    }
}
