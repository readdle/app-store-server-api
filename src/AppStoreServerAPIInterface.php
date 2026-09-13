<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;
use Readdle\AppStoreServerAPI\Response\CheckTestNotificationResponse;
use Readdle\AppStoreServerAPI\Response\DefaultConfigurationResponse;
use Readdle\AppStoreServerAPI\Response\ExtendRenewalDateResponse;
use Readdle\AppStoreServerAPI\Response\GetImageListResponse;
use Readdle\AppStoreServerAPI\Response\GetMessageListResponse;
use Readdle\AppStoreServerAPI\Response\HistoryResponse;
use Readdle\AppStoreServerAPI\Response\MassExtendRenewalDateResponse;
use Readdle\AppStoreServerAPI\Response\MassExtendRenewalDateStatusResponse;
use Readdle\AppStoreServerAPI\Response\NotificationHistoryResponse;
use Readdle\AppStoreServerAPI\Response\OrderLookupResponse;
use Readdle\AppStoreServerAPI\Response\RealtimeUrlResponse;
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
     * Get a customer's in-app purchase transaction history for your app
     *
     * @param string $transactionId The identifier of a transaction that belongs to the customer, and which may be an
     * original transaction identifier
     * @param array<string, mixed> $queryParams [optional] Query Parameters
     *
     * @throws AppStoreServerAPIException
     */
    public function getTransactionHistoryV2(string $transactionId, array $queryParams = []): HistoryResponse;

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
     * Sets the app account token value for a purchase the customer makes outside of your app
     * or updates its value in an existing transaction.
     *
     * @param string $originalTransactionId The original transaction identifier of the transaction to receive the app
     * account token update.
     * @param array<string, int|string> $requestBody The request body that contains a valid app account token value.
     *
     * @throws AppStoreServerAPIException
     */
    public function setAppAccountToken(string $originalTransactionId, array $requestBody): void;

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

    /**
     * Uploads an image to use for retention messaging.
     *
     * @param string $imageIdentifier A UUID you provide to uniquely identify the image you upload.
     * @param string $imageData The raw binary contents of the PNG image file to upload.
     * @param array<string, mixed> $queryParams [optional] Query Parameters, e.g. "imageSize"
     *
     * @throws AppStoreServerAPIException
     */
    public function uploadImage(string $imageIdentifier, string $imageData, array $queryParams = []): void;

    /**
     * Deletes a previously uploaded image.
     *
     * @param string $imageIdentifier The identifier of the image to delete.
     *
     * @throws AppStoreServerAPIException
     */
    public function deleteImage(string $imageIdentifier): void;

    /**
     * Gets the image identifier and state for all uploaded images.
     *
     * @throws AppStoreServerAPIException
     */
    public function getImageList(): GetImageListResponse;

    /**
     * Uploads a message to use for retention messaging.
     *
     * @param string $messageIdentifier A UUID you provide to uniquely identify the message you upload.
     * @param array<string, mixed> $requestBody The message text and optional image reference and bullet points.
     *
     * @throws AppStoreServerAPIException
     */
    public function uploadMessage(string $messageIdentifier, array $requestBody): void;

    /**
     * Deletes a previously uploaded message.
     *
     * @param string $messageIdentifier The identifier of the message to delete.
     *
     * @throws AppStoreServerAPIException
     */
    public function deleteMessage(string $messageIdentifier): void;

    /**
     * Gets the message identifier and state of all uploaded messages.
     *
     * @throws AppStoreServerAPIException
     */
    public function getMessageList(): GetMessageListResponse;

    /**
     * Configures a default message for a specific product in a specific locale.
     *
     * @param string $productId The product identifier for the default configuration.
     * @param string $locale The locale for the default configuration.
     * @param array<string, mixed> $requestBody The message identifier to configure as the default message.
     *
     * @throws AppStoreServerAPIException
     */
    public function configureDefaultMessage(string $productId, string $locale, array $requestBody): void;

    /**
     * Gets the default message for a specific product in a specific locale, if it's configured.
     *
     * @param string $productId The product identifier of the message.
     * @param string $locale The locale of the message.
     *
     * @throws AppStoreServerAPIException
     */
    public function getDefaultMessage(string $productId, string $locale): DefaultConfigurationResponse;

    /**
     * Deletes a default message for a product in a locale.
     *
     * @param string $productId The product ID of the default message configuration.
     * @param string $locale The locale of the default message configuration.
     *
     * @throws AppStoreServerAPIException
     */
    public function deleteDefaultMessage(string $productId, string $locale): void;

    /**
     * Configures the URL for your Get Retention Message endpoint in the sandbox and production environments.
     *
     * @param array<string, mixed> $requestBody The request body that includes your endpoint's URL.
     *
     * @throws AppStoreServerAPIException
     */
    public function configureRealtimeUrl(array $requestBody): void;

    /**
     * Gets the URL for real-time messages that points to your Get Retention Message endpoint, which you previously
     * configured.
     *
     * @throws AppStoreServerAPIException
     */
    public function getRealtimeUrl(): RealtimeUrlResponse;

    /**
     * Deletes the URL for your Get Retention Message endpoint, in the sandbox or production environments.
     *
     * @throws AppStoreServerAPIException
     */
    public function deleteRealtimeUrl(): void;
}
