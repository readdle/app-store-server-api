<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Generator;
use Readdle\AppStoreServerAPI\Environment;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Exception\UnimplementedContentTypeException;
use Readdle\AppStoreServerAPI\Util\JWT;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\TransactionInfo;

/**
 * @method static HistoryResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class HistoryResponse extends PageableResponse
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
     * @throws MalformedJWTException
     */
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        foreach ($properties['signedTransactions'] ?? [] as $signedTransactionInfo) {
            $this->items[] = TransactionInfo::createFromRawTransactionInfo(JWT::parse($signedTransactionInfo));
        }

        unset($properties['signedTransactions']);
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
     * Returns a Generator which iterates over an array of in-app purchase transactions for the customer.
     *
     * @return Generator<TransactionInfo>
     *
     * @throws HTTPRequestAborted
     * @throws HTTPRequestFailed
     * @throws MalformedResponseException
     * @throws UnimplementedContentTypeException
     */
    public function getTransactions(): Generator
    {
        return $this->getItems();
    }
}
