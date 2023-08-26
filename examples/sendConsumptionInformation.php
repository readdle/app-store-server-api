<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;
use Readdle\AppStoreServerAPI\RequestBody\ConsumptionRequestBody;

try {
    $api->sendConsumptionInformation($credentials['transactionId'], [
        'accountTenure' => ConsumptionRequestBody::ACCOUNT_TENURE__UNDECLARED,
        'appAccountToken' => '',
        'consumptionStatus' => ConsumptionRequestBody::CONSUMPTION_STATUS__UNDECLARED,
        'customerConsented' => true,
        'deliveryStatus' => ConsumptionRequestBody::DELIVERY_STATUS__DELIVERED,
        'lifetimeDollarsPurchased' => ConsumptionRequestBody::LIFETIME_DOLLARS_PURCHASED__UNDECLARED,
        'lifetimeDollarsRefunded' => ConsumptionRequestBody::LIFETIME_DOLLARS_REFUNDED__UNDECLARED,
        'platform' => ConsumptionRequestBody::PLATFORM__UNDECLARED,
        'playTime' => ConsumptionRequestBody::PLAY_TIME__UNDECLARED,
        'sampleContentProvided' => true,
        'userStatus' => ConsumptionRequestBody::USER_STATUS__UNDECLARED,
    ]);
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "Consumption information sent\n";
