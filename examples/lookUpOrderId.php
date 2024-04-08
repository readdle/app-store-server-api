<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';
require_once 'helper.php';

if (empty($credentials['orderId'])) {
    exit('You have to specify orderId in order to look it up');
}

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $orderLookup = $api->lookUpOrderId($credentials['orderId']);
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "Order Lookup\n";
echo "Status: {$orderLookup->getStatus()}\n\n";

foreach ($orderLookup->getTransactions() as $i => $transactionInfo) {
    echo "Transaction Info #$i\n";
    $header = "Transaction Info #$i";
    $delimiter = str_repeat('-', strlen($header));
    echo "$header\n$delimiter\n";
    printJsonSerializableEntity($transactionInfo);
    echo "\nAs JSON: " . json_encode($transactionInfo) . "\n\n";
}
