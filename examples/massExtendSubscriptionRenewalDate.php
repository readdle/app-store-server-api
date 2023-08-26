<?php
declare(strict_types=1);

['api' => $api, 'credentials' => $credentials] = require 'client.php';

if (empty($credentials['productId'])) {
    exit('You have to specify productId in order to request mass subscription renewal date extension');
}

use Readdle\AppStoreServerAPI\Exception\AppStoreServerAPIException;
use Readdle\AppStoreServerAPI\RequestBody\ExtendRenewalDateRequestBody;

try {
    $massExtendRenewalDateResponse = $api->massExtendSubscriptionRenewalDate([
        'extendByDays' => 1,
        'extendReasonCode' => ExtendRenewalDateRequestBody::EXTEND_REASON_CODE__UNDECLARED,
        'productId' => $credentials['productId'],
        'requestIdentifier' => vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4)),
        'storefrontCountryCodes' => ['POL']
    ]);
} catch (AppStoreServerAPIException $e) {
    exit($e->getMessage());
} catch (Exception $e) {
    exit("Wrong request body: {$e->getMessage()}");
}

echo "Mass extend renewal date\n\n";
echo "Request Identifier: {$massExtendRenewalDateResponse->getRequestIdentifier()}\n";
