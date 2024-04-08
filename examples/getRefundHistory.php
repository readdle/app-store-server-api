<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';
require_once 'helper.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $refundHistory = $api->getRefundHistory($credentials['transactionId']);
    $transactions = $refundHistory->getTransactions();
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "Refund history\n\n";

foreach ($transactions as $i => $transactionInfo) {
    $header = "Transaction Info #$i";
    $delimiter = str_repeat('-', strlen($header));
    echo "$header\n$delimiter\n";
    printJsonSerializableEntity($transactionInfo);
    echo "\nAs JSON: " . json_encode($transactionInfo) . "\n\n";
}
