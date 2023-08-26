<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Exception;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\WrongEnvironmentException;
use Readdle\AppStoreServerAPI\Exception\InvalidImplementationException;
use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Exception\UnimplementedContentTypeException;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\Request\ExtendSubscriptionRenewalDateRequest;
use Readdle\AppStoreServerAPI\Request\GetAllSubscriptionStatusesRequest;
use Readdle\AppStoreServerAPI\Request\GetNotificationHistoryRequest;
use Readdle\AppStoreServerAPI\Request\GetRefundHistoryRequest;
use Readdle\AppStoreServerAPI\Request\GetStatusOfSubscriptionRenewalDateExtensionsRequest;
use Readdle\AppStoreServerAPI\Request\GetTestNotificationStatusRequest;
use Readdle\AppStoreServerAPI\Request\GetTransactionHistoryRequest;
use Readdle\AppStoreServerAPI\Request\GetTransactionInfoRequest;
use Readdle\AppStoreServerAPI\Request\LookUpOrderIdRequest;
use Readdle\AppStoreServerAPI\Request\MassExtendSubscriptionRenewalDateRequest;
use Readdle\AppStoreServerAPI\Request\RequestTestNotificationRequest;
use Readdle\AppStoreServerAPI\Request\SendConsumptionInformationRequest;
use Readdle\AppStoreServerAPI\RequestBody\AbstractRequestBody;
use Readdle\AppStoreServerAPI\RequestBody\ConsumptionRequestBody;
use Readdle\AppStoreServerAPI\RequestBody\ExtendRenewalDateRequestBody;
use Readdle\AppStoreServerAPI\RequestBody\MassExtendRenewalDateRequestBody;
use Readdle\AppStoreServerAPI\RequestBody\NotificationHistoryRequestBody;
use Readdle\AppStoreServerAPI\RequestQueryParams\AbstractRequestQueryParams;
use Readdle\AppStoreServerAPI\RequestQueryParams\GetAllSubscriptionStatusesQueryParams;
use Readdle\AppStoreServerAPI\RequestQueryParams\GetNotificationHistoryQueryParams;
use Readdle\AppStoreServerAPI\RequestQueryParams\GetRefundHistoryQueryParams;
use Readdle\AppStoreServerAPI\RequestQueryParams\GetTransactionHistoryQueryParams;
use Readdle\AppStoreServerAPI\Response\AbstractResponse;
use Readdle\AppStoreServerAPI\Response\CheckTestNotificationResponse;
use Readdle\AppStoreServerAPI\Response\ExtendRenewalDateResponse;
use Readdle\AppStoreServerAPI\Response\MassExtendRenewalDateResponse;
use Readdle\AppStoreServerAPI\Response\MassExtendRenewalDateStatusResponse;
use Readdle\AppStoreServerAPI\Response\NotificationHistoryResponse;
use Readdle\AppStoreServerAPI\Response\OrderLookupResponse;
use Readdle\AppStoreServerAPI\Response\RefundHistoryResponse;
use Readdle\AppStoreServerAPI\Response\SendTestNotificationResponse;
use Readdle\AppStoreServerAPI\Response\HistoryResponse;
use Readdle\AppStoreServerAPI\Response\StatusResponse;
use Readdle\AppStoreServerAPI\Response\TransactionInfoResponse;

use function call_user_func;
use function in_array;
use function is_subclass_of;

final class AppStoreServerAPI implements AppStoreServerAPIInterface
{
    const PRODUCTION_BASE_URL = 'https://api.storekit.itunes.apple.com/inApps';
    const SANDBOX_BASE_URL = 'https://api.storekit-sandbox.itunes.apple.com/inApps';

    private string $environment;
    private Payload $payload;
    private Key $key;

    /**
     * @throws WrongEnvironmentException
     */
    public function __construct(string $environment, string $issuerId, string $bundleId, string $keyId, string $key)
    {
        if (!in_array($environment, [Environment::PRODUCTION, Environment::SANDBOX])) {
            throw new WrongEnvironmentException($environment);
        }

        $this->environment = $environment;
        $this->payload = new Payload($issuerId, $bundleId);
        $this->key = new Key($keyId, $key);
    }

    public function getTransactionHistory(string $transactionId, array $queryParams = []): HistoryResponse
    {
        /**
         * @var HistoryResponse $response
         */
        $response = $this->performRequest(
            GetTransactionHistoryRequest::class,
            HistoryResponse::class,
            ['transactionId' => $transactionId],
            new GetTransactionHistoryQueryParams($queryParams)
        );
        return $response;
    }

    public function getTransactionInfo(string $transactionId): TransactionInfoResponse
    {
        /**
         * @var TransactionInfoResponse $response
         */
        $response = $this->performRequest(
            GetTransactionInfoRequest::class,
            TransactionInfoResponse::class,
            ['transactionId' => $transactionId],
        );
        return $response;
    }

    public function getAllSubscriptionStatuses(string $transactionId, array $queryParams = []): StatusResponse
    {
        /**
         * @var StatusResponse $response
         */
        $response = $this->performRequest(
            GetAllSubscriptionStatusesRequest::class,
            StatusResponse::class,
            ['transactionId' => $transactionId],
            new GetAllSubscriptionStatusesQueryParams($queryParams)
        );
        return $response;
    }

    public function sendConsumptionInformation(string $transactionId, array $requestBody): void
    {
        $this->performRequest(
            SendConsumptionInformationRequest::class,
            null,
            ['transactionId' => $transactionId],
            null,
            new ConsumptionRequestBody($requestBody)
        );
    }

    public function lookUpOrderId(string $orderId): OrderLookupResponse
    {
        /**
         * @var OrderLookupResponse $response
         */
        $response = $this->performRequest(
            LookUpOrderIdRequest::class,
            OrderLookupResponse::class,
            ['orderId' => $orderId],
        );
        return $response;
    }

    public function getRefundHistory(string $transactionId): RefundHistoryResponse
    {
        /**
         * @var RefundHistoryResponse $response
         */
        $response = $this->performRequest(
            GetRefundHistoryRequest::class,
            RefundHistoryResponse::class,
            ['transactionId' => $transactionId],
            new GetRefundHistoryQueryParams()
        );
        return $response;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function extendSubscriptionRenewalDate(
        string $originalTransactionId,
        array $requestBody
    ): ExtendRenewalDateResponse {
        /**
         * @var ExtendRenewalDateResponse $response
         */
        $response = $this->performRequest(
            ExtendSubscriptionRenewalDateRequest::class,
            ExtendRenewalDateResponse::class,
            ['originalTransactionId' => $originalTransactionId],
            null,
            new ExtendRenewalDateRequestBody($requestBody)
        );
        return $response;

    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function massExtendSubscriptionRenewalDate(array $requestBody): MassExtendRenewalDateResponse
    {
        /**
         * @var MassExtendRenewalDateResponse $response
         */
        $response = $this->performRequest(
            MassExtendSubscriptionRenewalDateRequest::class,
            MassExtendRenewalDateResponse::class,
            [],
            null,
            new MassExtendRenewalDateRequestBody($requestBody)
        );
        return $response;

    }

    public function getStatusOfSubscriptionRenewalDateExtensionsRequest(
        string $productId,
        string $requestIdentifier
    ): MassExtendRenewalDateStatusResponse {
        /**
         * @var MassExtendRenewalDateStatusResponse $response
         */
        $response = $this->performRequest(
            GetStatusOfSubscriptionRenewalDateExtensionsRequest::class,
            MassExtendRenewalDateStatusResponse::class,
            [
                'productId' => $productId,
                'requestIdentifier' => $requestIdentifier,
            ]
        );
        return $response;
    }


    public function getNotificationHistory(array $requestBody): NotificationHistoryResponse
    {
        /**
         * @var NotificationHistoryResponse $response
         */
        $response = $this->performRequest(
            GetNotificationHistoryRequest::class,
            NotificationHistoryResponse::class,
            [],
            new GetNotificationHistoryQueryParams(),
            new NotificationHistoryRequestBody($requestBody)
        );
        return $response;
    }

    public function requestTestNotification(): SendTestNotificationResponse
    {
        /**
         * @var SendTestNotificationResponse $response
         */
        $response = $this->performRequest(RequestTestNotificationRequest::class, SendTestNotificationResponse::class);
        return $response;
    }

    public function getTestNotificationStatus(string $testNotificationToken): CheckTestNotificationResponse
    {
        /**
         * @var CheckTestNotificationResponse $response
         */
        $response = $this->performRequest(
            GetTestNotificationStatusRequest::class,
            CheckTestNotificationResponse::class,
            ['testNotificationToken' => $testNotificationToken],
        );
        return $response;

    }

    private function createRequest(
        string $requestClass,
        ?AbstractRequestQueryParams $queryParams,
        ?AbstractRequestBody $body
    ): AbstractRequest {
        /** @var AbstractRequest $request */
        $request = new $requestClass($this->key, $this->payload, $queryParams, $body);
        $request->setURLVars(['baseUrl' => $this->getBaseURL()]);
        return $request;
    }

    private function getBaseURL(): string
    {
        return $this->environment === Environment::PRODUCTION ? self::PRODUCTION_BASE_URL : self::SANDBOX_BASE_URL;
    }

    /**
     * @param array<string, mixed> $requestUrlVars
     *
     * @throws HTTPRequestAborted
     * @throws HTTPRequestFailed
     * @throws InvalidImplementationException
     * @throws MalformedResponseException
     * @throws UnimplementedContentTypeException
     */
    private function performRequest(
        string $requestClass,
        ?string $responseClass,
        array $requestUrlVars = [],
        ?AbstractRequestQueryParams $requestQueryParams = null,
        ?AbstractRequestBody $requestBody = null
    ): ?AbstractResponse {
        if (
            !is_subclass_of($requestClass, AbstractRequest::class)
            || (!empty($responseClass) && !is_subclass_of($responseClass, AbstractResponse::class))
        ) {
            throw new InvalidImplementationException($requestClass, $responseClass);
        }

        $request = $this->createRequest($requestClass, $requestQueryParams, $requestBody);

        if (!empty($requestUrlVars)) {
            $request->setURLVars($requestUrlVars);
        }

        $responseText = HTTPRequest::performRequest($request);

        if (empty($responseClass)) {
            return null;
        }

        return call_user_func([$responseClass, 'createFromString'], $responseText, $request);
    }
}
