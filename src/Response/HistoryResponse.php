<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Generator;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\JWT;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\TransactionInfo;

final class HistoryResponse extends PageableResponse
{
    /**
     * The app’s identifier in the App Store.
     */
    protected ?int $appAppleId = null;

    /**
     * The bundle identifier of the app.
     */
    protected string $bundleId;

    /**
     * The server environment in which you’re making the request, whether sandbox or production.
     */
    protected string $environment;

    /**
     * A Boolean value that indicates whether the App Store has more transactions than it returns in this response. If the value is true, use the revision token to request the next set of transactions.
     */
    protected bool $hasMore;

    /**
     * A token you use in a query to request the next set of transactions from the Get Transaction History endpoint.
     */
    protected string $revision;

    /**
     * An array of in-app purchase transactions of the customer, signed by Apple, in JSON Web Signature format.
     */
    protected array $signedTransactions = [];

    /**
     * An array of in-app purchase transactions of the customer, decoded.
     */
    protected array $transactions = [];

    /**
     * @throws MalformedJWTException
     */
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        parent::__construct($properties, $originalRequest);

        foreach ($this->signedTransactions as $signedTransaction) {
            $this->transactions[] = TransactionInfo::createFromPayload(JWT::parse($signedTransaction));
        }
    }

    public function transactionsIterator(): Generator
    {
        foreach ($this->transactions as $transaction) {
            yield $transaction;
        }
    }
}
