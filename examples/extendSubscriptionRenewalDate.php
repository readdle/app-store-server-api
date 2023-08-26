<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;
use Readdle\AppStoreServerAPI\RequestBody\ExtendRenewalDateRequestBody;

try {
    $extendRenewalDateResponse = $api->extendSubscriptionRenewalDate(
        $credentials['transactionId'],
        [
            'extendByDays' => 1,
            'extendReasonCode' => ExtendRenewalDateRequestBody::EXTEND_REASON_CODE__UNDECLARED,
            'requestIdentifier' => 'test',
        ]
    );
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
} catch (Exception $e) {
    exit("Wrong request body: {$e->getMessage()}");
}

echo "Extend renewal date\n\n";
echo "Effective Date: {$extendRenewalDateResponse->getEffectiveDate()}\n";
echo "Original Transaction ID: {$extendRenewalDateResponse->getOriginalTransactionId()}\n";
echo "Web Order Line Item ID: {$extendRenewalDateResponse->getWebOrderLineItemId()}\n";
