<?php
declare(strict_types=1);

require_once '../vendor/autoload.php';

use Readdle\AppStoreServerAPI\AppStoreServerAPI;
use Readdle\AppStoreServerAPI\Environment;
use Readdle\AppStoreServerAPI\Exception\WrongEnvironmentException;

$filename = 'credentials.json';
$keys = ['issuerId', 'bundleId', 'keyId', 'key', 'transactionId', 'env'];

if (!file_exists($filename)) {
    exit("You have to create `$filename` and put your [" . join(',', $keys) . "] there");
}

$credentialsRaw = file_get_contents($filename);

if (!$credentialsRaw) {
    exit("`$filename` could not be read or is empty");
}

$credentials = json_decode($credentialsRaw, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    exit("`$filename` contains invalid JSON");
}

if (array_diff($keys, array_keys($credentials))) {
    exit("Check `$filename` for presence of the following keys: " . join(',', $keys));
}

if (!in_array($credentials['env'], [Environment::PRODUCTION, Environment::SANDBOX])) {
    exit("`$filename` contains invalid environment name: {$credentials['env']}");
}

try {
    $api = new AppStoreServerAPI(
        $credentials['env'],
        $credentials['issuerId'],
        $credentials['bundleId'],
        $credentials['keyId'],
        $credentials['key']
    );
} catch (WrongEnvironmentException $e) {
    exit($e->getMessage());
}

return [
    'api' => $api,
    'credentials' => $credentials,
];
