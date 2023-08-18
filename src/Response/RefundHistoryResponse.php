<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Generator;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Exception\UnimplementedContentTypeException;
use Readdle\AppStoreServerAPI\Util\JWT;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\TransactionInfo;

/**
 * @method static RefundHistoryResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class RefundHistoryResponse extends PageableResponse
{
    /**
     * @throws MalformedJWTException
     */
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        foreach ($properties['signedTransactions'] ?? [] as $signedTransaction) {
            $this->items[] = TransactionInfo::createFromRawTransactionInfo(JWT::parse($signedTransaction));
        }

        unset($properties['signedTransactions']);
        parent::__construct($properties, $originalRequest);
    }

    /**
     * Returns a Generator which iterates over a list of refunded transactions.
     * The transactions are sorted in ascending order by revocationDate.
     *
     * @return Generator<TransactionInfo>
     *
     * @throws HTTPRequestAborted
     * @throws HTTPRequestFailed
     * @throws MalformedResponseException
     * @throws UnimplementedContentTypeException
     */
    public function getTransactions(): Generator
    {
        return $this->getItems();
    }
}
