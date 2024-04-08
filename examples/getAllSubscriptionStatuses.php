<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';
require_once 'helper.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $allSubscriptionStatuses = $api->getAllSubscriptionStatuses($credentials['transactionId']);
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "App Apple ID: {$allSubscriptionStatuses->getAppAppleId()}\n";
echo "Bundle ID: {$allSubscriptionStatuses->getBundleId()}\n";
echo "Environment: {$allSubscriptionStatuses->getEnvironment()}\n";
echo "\n\n";

foreach ($allSubscriptionStatuses->getData() as $subscriptionGroupIdentifierItem) {
    echo "Subscription Group Identifier: {$subscriptionGroupIdentifierItem->getSubscriptionGroupIdentifier()}\n";
    echo "Last Transactions\n=================\n\n";

    foreach ($subscriptionGroupIdentifierItem->getLastTransactions() as $lastTransactionsItem) {
        echo "Original Transaction ID: {$lastTransactionsItem->getOriginalTransactionId()}\n";
        echo "Status: {$lastTransactionsItem->getStatus()}\n";

        $transactionInfo = $lastTransactionsItem->getTransactionInfo();
        echo "\nTransaction Info\n----------------\n";
        printJsonSerializableEntity($transactionInfo);
        echo "\nAs JSON: " . json_encode($transactionInfo) . "\n";

        $renewalInfo = $lastTransactionsItem->getRenewalInfo();
        echo "\nRenewal Info\n------------\n";
        printJsonSerializableEntity($renewalInfo);
        echo "\nAs JSON: " . json_encode($renewalInfo) . "\n";
    }
}
