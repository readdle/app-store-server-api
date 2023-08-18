<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $notificationHistoryResponse = $api->getNotificationHistory(['startDate' => (time() - 60 * 60) * 1000]);
    $notificationHistoryResponseItems = $notificationHistoryResponse->getNotificationHistory();
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

foreach ($notificationHistoryResponseItems as $notificationHistoryResponseItem) {
    echo "\nSend attempts\n";

    foreach ($notificationHistoryResponseItem->getSendAttempts() as $sendAttempt) {
        echo "Attempt Date: {$sendAttempt->getAttemptDate()}\n";
        echo "Attempt Result: {$sendAttempt->getSendAttemptResult()}\n\n";
    }

    echo "Signed Payload: {$notificationHistoryResponseItem->getSignedPayload()}\n";
    echo 'ResponseBodyV2 as JSON: ' . json_encode($notificationHistoryResponseItem->getResponseBodyV2()) . "\n";

}
