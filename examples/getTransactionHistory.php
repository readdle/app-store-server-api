<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';
require_once 'helper.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $transactionHistory = $api->getTransactionHistory($credentials['transactionId'], ['sort' => 'ASCENDING']);
    $transactions = $transactionHistory->getTransactions();
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "App Apple ID: {$transactionHistory->getAppAppleId()}\n";
echo "Bundle ID: {$transactionHistory->getBundleId()}\n";
echo "Environment: {$transactionHistory->getEnvironment()}\n";
echo "\n";

foreach ($transactions as $i => $transactionInfo) {
    $header = "Transaction Info #$i";
    $delimiter = str_repeat('-', strlen($header));
    echo "$header\n$delimiter\n";
    printJsonSerializableEntity($transactionInfo);
    echo "\nAs JSON: " . json_encode($transactionInfo) . "\n\n";
}
