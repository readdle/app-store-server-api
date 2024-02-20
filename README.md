# About

This is a ***zero-dependencies\* pure PHP*** library that allows managing customer transactions using the [`App Store Server API`](https://developer.apple.com/documentation/appstoreserverapi) and handling server-to-server notifications by providing everything you need to implement the [`App Store Server Notifications V2`](https://developer.apple.com/documentation/appstoreservernotifications) endpoint.

<sub>* Zero-dependencies means this library doesn't rely on any third-party library. At the same time, this library relies on such essential PHP extensions as `json` and `openssl`</sub>

> **NOTE**
> 
> If you need to deal with receipts instead of (or additionally to) API, check out [this library](https://github.com/readdle/app-store-receipt-verification).

# Installation

Nothing special here, just use composer to install the package:

> composer install readdle/app-store-server-api

# Usage

### App Store Server API

API initialization:

```
try {
    $api = new \Readdle\AppStoreServerAPI\AppStoreServerAPI(
        \Readdle\AppStoreServerAPI\Environment::PRODUCTION,
        '1a2b3c4d-1234-4321-1111-1a2b3c4d5e6f',
        'com.readdle.MyBundle',
        'ABC1234DEF',
        "-----BEGIN PRIVATE KEY-----\n<base64-encoded private key goes here>\n-----END PRIVATE KEY-----"
    );
} catch (\Readdle\AppStoreServerAPI\Exception\WrongEnvironmentException $e) {
    exit($e->getMessage());
}
```

Performing API call:

```
try {
    $transactionHistory = $api->getTransactionHistory($transactionId, ['sort' => GetTransactionHistoryQueryParams::SORT__DESCENDING]);
    $transactions = $transactionHistory->getTransactions();
} catch (\Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException $e) {
    exit($e->getMessage());
}
```

### App Store Server Notifications

```
try {
    $responseBodyV2 = \Readdle\AppStoreServerAPI\ResponseBodyV2::createFromRawNotification(
        '{"signedPayload":"..."}',
        \Readdle\AppStoreServerAPI\Util\Helper::toPEM(file_get_contents('https://www.apple.com/certificateauthority/AppleRootCA-G3.cer'))
    );
} catch (\Readdle\AppStoreServerAPI\Exception\AppStoreServerNotificationException $e) {
    exit('Server notification could not be processed: ' . $e->getMessage());
}
```

# Examples

In `examples/` directory you can find examples for all implemented endpoints. Initialization of the API client is separated into `client.php` and used in all examples.

In order to run examples you have to create `credentials.json` and/or `notifications.json` inside `examples/` directory.

`credentials.json` structure should be as follows:

```
{
  "env": "Production",
  "issuerId": "1a2b3c4d-1234-4321-1111-1a2b3c4d5e6f",
  "bundleId": "com.readdle.MyBundle",
  "keyId": "ABC1234DEF",
  "key": "-----BEGIN PRIVATE KEY-----\n<base64-encoded private key goes here>\n-----END PRIVATE KEY-----",
  "orderId": "ABC1234DEF",
  "transactionId": "123456789012345"
}
```

In most examples `transactionId` is used. Please, consider that `transactionId` is related to `environment`, so if you put `transactionId` from the sandbox the `environment` property should be `Sandbox` as well, otherwise you'll get `{"errorCode":4040010,"errorMessage":"Transaction id not found."}` error. 

For `Order ID lookup` you have to specify `orderId`. This endpoint (and, consequently, the example) is not available in the sandbox environment.

`notification.json` structure is the same as you receive it in your server-to-server notification endpoint:

```
{"signedPayload":"<JWT token goes here>"}
```

# What is covered

### In-app purchase history

#### [Get Transaction History](https://developer.apple.com/documentation/appstoreserverapi/get_transaction_history)

`AppStoreServerAPI::getTransactionHistory(string $transactionId, array $queryParams)`

Get a customer’s in-app purchase transaction history for your app.

### Transaction Info

#### [Get Transaction Info](https://developer.apple.com/documentation/appstoreserverapi/get_transaction_info)

`AppStoreServerAPI::getTransactionInfo(string $transactionId)`

Get information about a single transaction for your app.

### Subscription status

#### [Get All Subscription Statuses](https://developer.apple.com/documentation/appstoreserverapi/get_all_subscription_statuses)

`AppStoreServerAPI::getAllSubscriptionStatuses(string $transactionId, array $queryParams = [])`

Get the statuses for all of a customer’s auto-renewable subscriptions in your app.

### Consumption information

#### [Send Consumption Information](https://developer.apple.com/documentation/appstoreserverapi/send_consumption_information)

`AppStoreServerAPI::sendConsumptionInformation(string $transactionId, array $requestBody)`

Send consumption information about a consumable in-app purchase to the App Store after your server receives a consumption request notification.

### Order ID lookup

#### [Look Up Order ID](https://developer.apple.com/documentation/appstoreserverapi/look_up_order_id)

`AppStoreServerAPI::lookUpOrderId(string $orderId)`

Get a customer’s in-app purchases from a receipt using the order ID.

### Refund lookup

#### [Get Refund History](https://developer.apple.com/documentation/appstoreserverapi/get_refund_history)

`AppStoreServerAPI::getRefundHistory(string $transactionId)`

Get a list of all of a customer’s refunded in-app purchases for your app.

### Subscription-renewal-date extension

#### [Extend a Subscription Renewal Date](https://developer.apple.com/documentation/appstoreserverapi/extend_a_subscription_renewal_date)

`AppStoreServerAPI::extendSubscriptionRenewalDate(string $originalTransactionId, array $requestBody)`

Extends the renewal date of a customer’s active subscription using the original transaction identifier.

#### [Extend Subscription Renewal Dates for All Active Subscribers](https://developer.apple.com/documentation/appstoreserverapi/extend_subscription_renewal_dates_for_all_active_subscribers)

`AppStoreServerAPI::massExtendSubscriptionRenewalDate(array $requestBody)`

Uses a subscription’s product identifier to extend the renewal date for all of its eligible active subscribers.

#### [Get Status of Subscription Renewal Date Extensions](https://developer.apple.com/documentation/appstoreserverapi/get_status_of_subscription_renewal_date_extensions)

`AppStoreServerAPI::getStatusOfSubscriptionRenewalDateExtensionsRequest(string $productId, string $requestIdentifier)`

Checks whether a renewal date extension request completed, and provides the final count of successful or failed extensions.

### App Store Server Notifications history

#### [Get Notification History](https://developer.apple.com/documentation/appstoreserverapi/get_notification_history)

`AppStoreServerAPI::getNotificationHistory(array $requestBody)`

Get a list of notifications that the App Store server attempted to send to your server.

### App Store Server Notifications testing

#### [Request a Test Notification](https://developer.apple.com/documentation/appstoreserverapi/request_a_test_notification)

`AppStoreServerAPI::requestTestNotification()`

Ask App Store Server Notifications to send a test notification to your server.

#### [Get Test Notification Status](https://developer.apple.com/documentation/appstoreserverapi/get_test_notification_status)

`AppStoreServerAPI::getTestNotificationStatus(string $testNotificationToken)`

Check the status of the test App Store server notification sent to your server.
