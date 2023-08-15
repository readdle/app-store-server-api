<?php
declare(strict_types=1);

require_once '../vendor/autoload.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerNotificationException;
use Readdle\AppStoreServerAPI\ResponseBodyV2;
use Readdle\AppStoreServerAPI\Util\Helper;


$filename = 'notification.json';
$notification = file_get_contents($filename);

if (!$notification) {
    exit("`$filename` could not be read or is empty");
}

try {
    $responseBodyV2 = ResponseBodyV2::createFromRawNotification(
        $notification,
        Helper::toPEM(file_get_contents('https://www.apple.com/certificateauthority/AppleRootCA-G3.cer'))
    );
} catch (AppStoreServerNotificationException $e) {
    exit('Server notification could not be processed: ' . $e->getMessage());
}

echo "Notification type: {$responseBodyV2->getNotificationType()}\n";
echo "Notification subtype: {$responseBodyV2->getSubtype()}\n";
echo "Notification UUID: {$responseBodyV2->getNotificationUUID()}\n";
echo "Version: {$responseBodyV2->getVersion()}\n";
echo "Signed date: {$responseBodyV2->getSignedDate()}\n";

echo "\nAs JSON: " . json_encode($responseBodyV2) . "\n";

$appMetadata = $responseBodyV2->getAppMetadata();
echo "\nApp Metadata\n";
echo "App Apple ID: {$appMetadata->getAppAppleId()}\n";
echo "Bundle ID: {$appMetadata->getBundleId()}\n";
echo "Bundle Version: {$appMetadata->getBundleVersion()}\n";
echo "Environment: {$appMetadata->getEnvironment()}\n";

echo "\nAs JSON: " . json_encode($appMetadata) . "\n";

$transactionInfo = $appMetadata->getTransactionInfo();

if ($transactionInfo) {
    echo "\nTransaction Info\n";
    echo "App Account Token: {$transactionInfo->getAppAccountToken()}\n";
    echo "Bundle ID: {$transactionInfo->getBundleId()}\n";
    echo "Environment: {$transactionInfo->getEnvironment()}\n";
    echo "Expires Date: {$transactionInfo->getExpiresDate()}\n";
    echo "In-App Ownership Type: {$transactionInfo->getInAppOwnershipType()}\n";
    echo "Is Upgraded: {$transactionInfo->getIsUpgraded()}\n";
    echo "Offer Identifier: {$transactionInfo->getOfferIdentifier()}\n";
    echo "Offer Type: {$transactionInfo->getOfferType()}\n";
    echo "Original Purchase Date: {$transactionInfo->getOriginalPurchaseDate()}\n";
    echo "Original Transaction ID: {$transactionInfo->getOriginalTransactionId()}\n";
    echo "Product ID: {$transactionInfo->getProductId()}\n";
    echo "Purchase Date: {$transactionInfo->getPurchaseDate()}\n";
    echo "Quantity: {$transactionInfo->getQuantity()}\n";
    echo "Revocation Date: {$transactionInfo->getRevocationDate()}\n";
    echo "Revocation Reason: {$transactionInfo->getRevocationReason()}\n";
    echo "Signed Date: {$transactionInfo->getSignedDate()}\n";
    echo "Subscription Group Identifier: {$transactionInfo->getSubscriptionGroupIdentifier()}\n";
    echo "Transaction ID: {$transactionInfo->getTransactionId()}\n";
    echo "Type: {$transactionInfo->getType()}\n";
    echo "Web Order Line Item ID: {$transactionInfo->getWebOrderLineItemId()}\n";

    echo "\nAs JSON: " . json_encode($transactionInfo) . "\n";
}

$renewalInfo = $appMetadata->getRenewalInfo();

if ($renewalInfo) {
    echo "\nRenewal Info\n";
    echo "Auto Renew Product ID: {$renewalInfo->getAutoRenewProductId()}\n";
    echo "Auto Renew Status: {$renewalInfo->getAutoRenewStatus()}\n";
    echo "Environment: {$renewalInfo->getEnvironment()}\n";
    echo "Expiration Intent: {$renewalInfo->getExpirationIntent()}\n";
    echo "Grace Period Expires Date: {$renewalInfo->getGracePeriodExpiresDate()}\n";
    echo "Is In Billing Retry Period: {$renewalInfo->getIsInBillingRetryPeriod()}\n";
    echo "Offer Identifier: {$renewalInfo->getOfferIdentifier()}\n";
    echo "Offer Type: {$renewalInfo->getOfferType()}\n";
    echo "Original Transaction ID: {$renewalInfo->getOriginalTransactionId()}\n";
    echo "Price Increase Status: {$renewalInfo->getPriceIncreaseStatus()}\n";
    echo "Product ID: {$renewalInfo->getProductId()}\n";
    echo "Recent Subscription Start Date: {$renewalInfo->getRecentSubscriptionStartDate()}\n";
    echo "Signed Date: {$renewalInfo->getSignedDate()}\n";

    echo "\nAs JSON: " . json_encode($renewalInfo) . "\n";
}
