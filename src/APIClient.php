<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\InvalidArgumentException;
use Readdle\AppStoreServerAPI\Exception\InvalidImplementationException;
use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\Request\GetTransactionHistory;
use Readdle\AppStoreServerAPI\Request\RequestTestNotification;
use Readdle\AppStoreServerAPI\Response\AbstractResponse;
use Readdle\AppStoreServerAPI\Response\SendTestNotificationResponse;
use Readdle\AppStoreServerAPI\Response\HistoryResponse;
use function call_user_func;
use function in_array;
use function is_subclass_of;

final class APIClient
{
    const PRODUCTION_BASE_URL = 'https://api.storekit.itunes.apple.com/inApps';
    const SANDBOX_BASE_URL = 'https://api.storekit-sandbox.itunes.apple.com/inApps';

    const ENVIRONMENT_PRODUCTION = 1;
    const ENVIRONMENT_SANDBOX = 2;

    private int $environment;
    private Payload $payload;
    private Key $key;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(int $environment, string $issuerId, string $bundleId, string $key, string $keyId)
    {
        if (!in_array($environment, [self::ENVIRONMENT_PRODUCTION, self::ENVIRONMENT_SANDBOX])) {
            throw new InvalidArgumentException(
                'Environment should be either ENVIRONMENT_PRODUCTION (' . self::ENVIRONMENT_PRODUCTION . ')'
                . ' or ENVIRONMENT_SANDBOX (' . self::ENVIRONMENT_SANDBOX . '), ' . $environment . ' is passed'
            );
        }

        $this->environment = $environment;
        $this->payload = new Payload($issuerId, $bundleId);
        $this->key = new Key($key, $keyId);
    }

    /**
     * Get a customer’s in-app purchase transaction history for your app
     *
     * @throws HTTPRequestAborted
     * @throws HTTPRequestFailed
     * @throws InvalidImplementationException
     * @throws MalformedResponseException
     */
    public function getTransactionHistory(string $originalTransactionId, array $queryParams = []): HistoryResponse
    {
        /**
         * @var HistoryResponse $response
         */
        $response = $this->performRequest(
            GetTransactionHistory::class,
            HistoryResponse::class,
            ['originalTransactionId' => $originalTransactionId],
            $queryParams
        );
        return $response;
    }

    /**
     * Ask App Store Server Notifications to send a test notification to your server
     *
     * @throws HTTPRequestAborted
     * @throws HTTPRequestFailed
     * @throws InvalidImplementationException
     * @throws MalformedResponseException
     */
    public function requestTestNotification(): SendTestNotificationResponse
    {
        /**
         * @var SendTestNotificationResponse $response
         */
        $response = $this->performRequest(RequestTestNotification::class, SendTestNotificationResponse::class);
        return $response;
    }

    private function createRequest(string $requestClass, array $queryParams = []): AbstractRequest
    {
        /** @var AbstractRequest $request */
        $request = new $requestClass($this->key, $this->payload, $queryParams);
        $request->setURLParams(['baseUrl' => $this->getBaseURL()]);
        return $request;
    }

    private function getBaseURL(): string
    {
        return match($this->environment) {
            self::ENVIRONMENT_PRODUCTION => self::PRODUCTION_BASE_URL,
            self::ENVIRONMENT_SANDBOX => self::SANDBOX_BASE_URL,
        };
    }

    /**
     * @throws HTTPRequestAborted
     * @throws HTTPRequestFailed
     * @throws InvalidImplementationException
     * @throws MalformedResponseException
     */
    private function performRequest(
        string $requestClass,
        string $responseClass,
        array $requestUrlParams = [],
        array $requestQueryParams = []
    ): AbstractResponse {
        if (
            !is_subclass_of($requestClass, AbstractRequest::class)
            || !is_subclass_of($responseClass, AbstractResponse::class)
        ) {
            throw new InvalidImplementationException($requestClass, $responseClass);
        }

        $request = $this->createRequest($requestClass, $requestQueryParams);

        if (!empty($requestUrlParams)) {
            $request->setURLParams($requestUrlParams);
        }

        $responseText = HTTPRequest::performRequest($request);

        return call_user_func([$responseClass, 'createFromString'], $responseText, $request);
    }
}
