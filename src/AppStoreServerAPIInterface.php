<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;
use Readdle\AppStoreServerAPI\Response\CheckTestNotificationResponse;
use Readdle\AppStoreServerAPI\Response\HistoryResponse;
use Readdle\AppStoreServerAPI\Response\NotificationHistoryResponse;
use Readdle\AppStoreServerAPI\Response\OrderLookupResponse;
use Readdle\AppStoreServerAPI\Response\RefundHistoryResponse;
use Readdle\AppStoreServerAPI\Response\SendTestNotificationResponse;
use Readdle\AppStoreServerAPI\Response\StatusResponse;
use Readdle\AppStoreServerAPI\Response\TransactionInfoResponse;

interface AppStoreServerAPIInterface
{
    /**
     * @param string $environment Either Environment::PRODUCTION or Environment::SANDBOX
     * @param string $issuerId Your issuer ID from the Keys page in App Store Connect (Ex: "57246542-96fe-1a63-e053-0824d011072a")
     * @param string $bundleId Your app's bundle ID (Ex: “com.example.TestBundleId2021”)
     * @param string $key The private key associated with the key ID
     * @param string $keyId Your private key ID from App Store Connect (Ex: 2X9R4HXF34)
     */
    public function __construct(string $environment, string $issuerId, string $bundleId, string $keyId, string $key);

    /**
     * Get a customer's in-app purchase transaction history for your app
     *
     * @param string $transactionId The identifier of a transaction that belongs to the customer, and which may be an
     * original transaction identifier
     * @param array<string, mixed> $queryParams [optional] Query Parameters
     *
     * @throws AppStoreServerAPIException
     */
    public function getTransactionHistory(string $transactionId, array $queryParams = []): HistoryResponse;

    /**
     * Get information about a single transaction for your app.
     *
     * @param string $transactionId The identifier of a transaction that belongs to the customer, and which may be an
     * original transaction identifier
     *
     * @throws AppStoreServerAPIException
     */
    public function getTransactionInfo(string $transactionId): TransactionInfoResponse;

    /**
     * Get the statuses for all of a customer's auto-renewable subscriptions in your app.
     *
     * @param string $transactionId The identifier of a transaction that belongs to the customer, and which may be an
     * original transaction identifier
     * @param array<string, mixed> $queryParams [optional] Query Parameters
     *
     * @throws AppStoreServerAPIException
     */
    public function getAllSubscriptionStatuses(string $transactionId, array $queryParams = []): StatusResponse;

    /**
     * Get a customer's in-app purchases from a receipt using the order ID.
     *
     * @param string $orderId The order ID for in-app purchases that belong to the customer.
     *
     * @throws AppStoreServerAPIException
     */
    public function lookUpOrderId(string $orderId): OrderLookupResponse;

    /**
     * Get a list of all of a customer's refunded in-app purchases for your app.
     *
     * @param string $transactionId The identifier of a transaction that belongs to the customer, and which may be an
     * original transaction identifier
     *
     * @throws AppStoreServerAPIException
     */
    public function getRefundHistory(string $transactionId): RefundHistoryResponse;

    /**
     * Get a list of notifications that the App Store server attempted to send to your server.
     *
     * @param array<string, mixed> $requestBody The request body that includes the start and end dates,
     * and optional query constraints.
     *
     * @throws AppStoreServerAPIException
     */
    public function getNotificationHistory(array $requestBody): NotificationHistoryResponse;

    /**
     * Ask App Store Server Notifications to send a test notification to your server
     *
     * @throws AppStoreServerAPIException
     */
    public function requestTestNotification(): SendTestNotificationResponse;

    /**
     * Check the status of the test App Store server notification sent to your server.
     *
     * @param string $testNotificationToken The token that uniquely identifies a test, that you receive when you call
     * APIClientInterface::requestTestNotification().
     *
     * @throws AppStoreServerAPIException
     */
    public function getTestNotificationStatus(string $testNotificationToken): CheckTestNotificationResponse;
}
