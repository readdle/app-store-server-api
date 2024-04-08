<?php
declare(strict_types=1);

require_once '../vendor/autoload.php';
require_once 'helper.php';

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
    echo "\nTransaction Info\n----------------\n";
    printJsonSerializableEntity($transactionInfo);
    echo "\nAs JSON: " . json_encode($transactionInfo) . "\n";
}

$renewalInfo = $appMetadata->getRenewalInfo();

if ($renewalInfo) {
    echo "\nRenewal Info\n------------\n";
    printJsonSerializableEntity($renewalInfo);
    echo "\nAs JSON: " . json_encode($renewalInfo) . "\n";
}
