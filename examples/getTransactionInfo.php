<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';
require_once 'helper.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $transactionInfoResponse = $api->getTransactionInfo($credentials['transactionId']);
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

$transactionInfo = $transactionInfoResponse->getTransactionInfo();

printJsonSerializableEntity($transactionInfo);
echo "\nAs JSON: " . json_encode($transactionInfo) . "\n\n";
