<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Util\JWT;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\TransactionInfo;

/**
 * @method static TransactionInfoResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class TransactionInfoResponse extends AbstractResponse
{
    /**
     * A customer's in-app purchase transaction.
     */
    protected TransactionInfo $transactionInfo;

    /**
     * @throws MalformedJWTException
     */
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        $this->transactionInfo = TransactionInfo::createFromRawTransactionInfo(JWT::parse($properties['signedTransactionInfo']));
        unset($properties['signedTransactionInfo']);
        parent::__construct($properties, $originalRequest);
    }

    /**
     * Returns a customer's in-app purchase transaction.
     */
    public function getTransactionInfo(): TransactionInfo
    {
        return $this->transactionInfo;
    }
}
