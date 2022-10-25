<?php
declare(strict_types=1);

use Readdle\AppStoreServerAPI\APIClient;
use Readdle\AppStoreServerAPI\Exception\AppStoreServerNotificationException;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\InvalidArgumentException;
use Readdle\AppStoreServerAPI\Exception\InvalidImplementationException;
use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Notification\ResponseBodyV2;
use Readdle\AppStoreServerAPI\Util\Helper;

require_once 'vendor/autoload.php';

function dump($string): void
{
    var_export($string);
    echo "\n";
}

//
// App Store Server API
//

function sampleAPI($issuerId, $bundleId, $privateKey, $keyId, $transactionId): void
{
    try {
        $client = new APIClient(APIClient::ENVIRONMENT_SANDBOX, $issuerId, $bundleId, $privateKey, $keyId);
    } catch (InvalidArgumentException $e) {
        exit($e->getMessage());
    }

    try {
        dump($client->requestTestNotification()->getTestNotificationToken());
        dump($client->getTransactionHistory($transactionId));
    } catch (HTTPRequestAborted $e) {
        dump('HTTP request aborted: ' . $e->getMessage());
    } catch (HTTPRequestFailed $e) {
        dump('HTTP request failed with status code ' . $e->getCode() . ': ' . $e->getMessage());
    } catch (MalformedResponseException $e) {
        dump('Malformed response: ' . $e->getMessage());
    } catch (InvalidImplementationException $e) {
        dump('An internal error happened inside the library, please report it to the developer: ');
        dump($e->getMessage());
    }
}

$bundleId = 'com.tester.app';
$issuerId = '12345678-abcd-abcd-abcd-123456789abc';
$keyId = 'ABCDEFGHIJ';
$privateKey = <<<EOD
-----BEGIN PRIVATE KEY-----
...
-----END PRIVATE KEY-----
EOD;
$transactionId = '2000000000000000';

sampleAPI($issuerId, $bundleId, $privateKey, $keyId, $transactionId);

//
// App Store Server Notifications V2
//

function sampleNotification($notification): void
{
    try {
        dump(
            ResponseBodyV2::createFromRawNotification(
                $notification,
                Helper::DER2PEM(file_get_contents('https://www.apple.com/certificateauthority/AppleRootCA-G3.cer'))
            )
        );
    } catch (AppStoreServerNotificationException $e) {
        dump('Server notification could not be processed: ' . $e->getMessage());
    }
}

$s2sNotification = '{"signedPayload":"aaa.bbb.ccc"}';

sampleNotification($s2sNotification);
