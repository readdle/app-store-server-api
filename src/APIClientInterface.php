<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Request\GetTransactionHistoryQueryParams;
use Readdle\AppStoreServerAPI\Response\HistoryResponse;

interface APIClientInterface
{
    public function __construct(int $environment, string $issuerId, string $bundleId, string $key, string $keyId);

    public function getTransactionHistory(
        string $originalTransactionId,
        ?GetTransactionHistoryQueryParams $queryParams = null
    ): HistoryResponse;
}
