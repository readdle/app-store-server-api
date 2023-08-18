<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $testNotification = $api->requestTestNotification();
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "Test notification token: {$testNotification->getTestNotificationToken()}\n";

try {
    $testNotificationStatus = $api->getTestNotificationStatus($testNotification->getTestNotificationToken());
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

$notificationHistoryResponseItem = $testNotificationStatus->getNotificationHistoryResponseItem();

echo "\nSend attempts\n";

foreach ($notificationHistoryResponseItem->getSendAttempts() as $sendAttempt) {
    echo "Attempt Date: {$sendAttempt->getAttemptDate()}\n";
    echo "Attempt Result: {$sendAttempt->getSendAttemptResult()}\n";
}

echo "Signed Payload: {$notificationHistoryResponseItem->getSignedPayload()}\n";
echo 'ResponseBodyV2 as JSON: ' . json_encode($notificationHistoryResponseItem->getResponseBodyV2()) . "\n";
