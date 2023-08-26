<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;
use Readdle\AppStoreServerAPI\Response\CheckTestNotificationResponse;
use Readdle\AppStoreServerAPI\Response\ExtendRenewalDateResponse;
use Readdle\AppStoreServerAPI\Response\HistoryResponse;
use Readdle\AppStoreServerAPI\Response\MassExtendRenewalDateResponse;
use Readdle\AppStoreServerAPI\Response\MassExtendRenewalDateStatusResponse;
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
     * Send consumption information about a consumable in-app purchase to the App Store after your server receives
     * a consumption request notification.
     *
     * @param string $transactionId The transaction identifier for which you’re providing consumption information.
     * You receive this identifier in the CONSUMPTION_REQUEST notification the App Store sends to your server.
     * @param array<string, int|string> $requestBody The request body containing consumption information.
     *
     * @throws AppStoreServerAPIException
     */
    public function sendConsumptionInformation(string $transactionId, array $requestBody): void;

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
     * Extends the renewal date of a customer’s active subscription using the original transaction identifier.
     *
     * @param string $originalTransactionId The original transaction identifier of the subscription receiving a renewal
     * date extension.
     * @param array<string, int|string> $requestBody The request body that contains subscription-renewal-extension
     * data for an individual subscription.
     *
     * @throws AppStoreServerAPIException
     */
    public function extendSubscriptionRenewalDate(
        string $originalTransactionId,
        array $requestBody
    ): ExtendRenewalDateResponse;

    /**
     * Uses a subscription’s product identifier to extend the renewal date for all of its eligible active subscribers.
     *
     * @param array<string, int|string> $requestBody
     *
     * @throws AppStoreServerAPIException
     */
    public function massExtendSubscriptionRenewalDate(array $requestBody): MassExtendRenewalDateResponse;

    /**
     * Checks whether a renewal date extension request completed, and provides the final count of successful
     * or failed extensions.
     *
     * @param string $productId The product identifier of the auto-renewable subscription that you request
     * a renewal-date extension for.
     * @param string $requestIdentifier The UUID that represents your request to
     * the massExtendSubscriptionRenewalDate() endpoint.
     *
     * @throws AppStoreServerAPIException
     */
    public function getStatusOfSubscriptionRenewalDateExtensionsRequest(
        string $productId,
        string $requestIdentifier
    ): MassExtendRenewalDateStatusResponse;

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
