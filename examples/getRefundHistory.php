<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $refundHistory = $api->getRefundHistory($credentials['transactionId']);
    $transactions = $refundHistory->getTransactions();
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "Refund history\n\n";

foreach ($transactions as $i => $transactionInfo) {
    echo "Transaction Info #$i\n";
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

    echo "\nAs JSON: " . json_encode($transactionInfo) . "\n\n";
}
