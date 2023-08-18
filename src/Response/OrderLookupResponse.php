<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Util\JWT;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\TransactionInfo;

/**
 * @method static OrderLookupResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class OrderLookupResponse extends AbstractResponse
{
    const STATUS__VALID = 0;
    const STATUS__INVALID = 1;

    /**
     * The status that indicates whether the order ID is valid.
     */
    protected int $status;

    /**
     * An array of in-app purchase transactions that are part of order.
     *
     * @var array<int, TransactionInfo>
     */
    protected array $transactions = [];

    /**
     * @throws MalformedJWTException
     */
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        foreach ($properties['signedTransactions'] ?? [] as $signedTransaction) {
            $this->transactions[] = TransactionInfo::createFromRawTransactionInfo(JWT::parse($signedTransaction));
        }

        unset($properties['signedTransactions']);
        parent::__construct($properties, $originalRequest);
    }

    /**
     * Returns the status that indicates whether the order ID is valid.
     *
     * @return self::STATUS__*
     */
    public function getStatus(): int
    {
        /** @phpstan-ignore-next-line */
        return $this->status;
    }

    /**
     *  Returns an array of in-app purchase transactions that are part of order.
     *
     * @return array<TransactionInfo>
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }
}
