<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';

if (empty($credentials['productId'])) {
    exit('You have to specify productId in order to get status of subscription renewal date extension request');
}

if (empty($credentials['requestIdentifier'])) {
    exit('You have to specify requestIdentifier in order to get status of subscription renewal date extension request');
}

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;

try {
    $massExtendRenewalDateStatusResponse = $api->getStatusOfSubscriptionRenewalDateExtensionsRequest(
        $credentials['productId'],
        $credentials['requestIdentifier'],
    );
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
}

echo "Mass extend renewal date status\n\n";

echo "Complete Date: {$massExtendRenewalDateStatusResponse->getCompleteDate()}\n";
echo 'Is Complete: ' . ($massExtendRenewalDateStatusResponse->isComplete() ? 'yes' : 'no') . "\n";
echo "Request Identifier: {$massExtendRenewalDateStatusResponse->getRequestIdentifier()}\n";
echo "Failed Count: {$massExtendRenewalDateStatusResponse->getFailedCount()}\n";
echo "Succeeded Count: {$massExtendRenewalDateStatusResponse->getSucceededCount()}\n";
