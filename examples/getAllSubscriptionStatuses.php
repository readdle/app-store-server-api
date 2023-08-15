<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;


try {
    $allSubscriptionStatuses = $api->getAllSubscriptionStatuses($credentials['transactionId']);
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "App Apple ID: {$allSubscriptionStatuses->getAppAppleId()}\n";
echo "Bundle ID: {$allSubscriptionStatuses->getBundleId()}\n";
echo "Environment: {$allSubscriptionStatuses->getEnvironment()}\n";
echo "\n";

foreach ($allSubscriptionStatuses->getData() as $subscriptionGroupIdentifierItem) {
    echo "Subscription Group Identifier: {$subscriptionGroupIdentifierItem->getSubscriptionGroupIdentifier()}\n";
    echo "Last Transactions\n";

    foreach ($subscriptionGroupIdentifierItem->getLastTransactions() as $i => $lastTransactionsItem) {
        echo "Original Transaction ID: {$lastTransactionsItem->getOriginalTransactionId()}\n";
        echo "Status: {$lastTransactionsItem->getStatus()}\n";

        $transactionInfo = $lastTransactionsItem->getTransactionInfo();
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

        $renewalInfo = $lastTransactionsItem->getRenewalInfo();
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
}
