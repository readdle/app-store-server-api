<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Environment;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\SubscriptionGroupIdentifierItem;

/**
 * @method static StatusResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class StatusResponse extends AbstractResponse
{
    /**
     * The app's identifier in the App Store.
     * This property is available for apps that are downloaded from the App Store; it isn't present in the sandbox
     * environment.
     */
    protected ?int $appAppleId = null;

    /**
     * The bundle identifier of the app.
     */
    protected string $bundleId;

    /**
     * The server environment in which you're making the request, whether sandbox or production.
     */
    protected string $environment;

    /**
     * An array of information for auto-renewable subscriptions,
     * including transaction information and renewal information.
     *
     * @var array<SubscriptionGroupIdentifierItem>
     */
    protected array $data = [];

    /**
     * @param array<string, mixed> $properties
     *
     * @throws MalformedJWTException
     */
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        foreach ($properties['data'] as $rawSubscriptionGroupIdentifierItem) {
            $this->data[] = SubscriptionGroupIdentifierItem::createFromRawItem($rawSubscriptionGroupIdentifierItem);
        }

        unset($properties['data']);
        parent::__construct($properties, $originalRequest);
    }

    /**
     * Returns the app's identifier in the App Store.
     * This property is available for apps that are downloaded from the App Store; it isn't present in the sandbox
     * environment.
     */
    public function getAppAppleId(): ?int
    {
        return $this->appAppleId;
    }

    /**
     * Returns the bundle identifier of the app.
     */
    public function getBundleId(): string
    {
        return $this->bundleId;
    }

    /**
     * Returns the server environment in which you're making the request, whether sandbox or production.
     *
     * @return Environment::PRODUCTION|Environment::SANDBOX
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Returns an array of information for auto-renewable subscriptions,
     * including transaction information and renewal information.
     *
     * @return array<SubscriptionGroupIdentifierItem>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
