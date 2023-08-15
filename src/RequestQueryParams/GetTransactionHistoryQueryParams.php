<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestQueryParams;

final class GetTransactionHistoryQueryParams extends PageableQueryParams
{
    const PRODUCT_TYPE__AUTO_RENEWABLE = 'AUTO_RENEWABLE';
    const PRODUCT_TYPE__NON_RENEWABLE = 'NON_RENEWABLE';
    const PRODUCT_TYPE__CONSUMABLE = 'CONSUMABLE';
    const PRODUCT_TYPE__NON_CONSUMABLE = 'NON_CONSUMABLE';

    const SORT__ASCENDING = 'ASCENDING';
    const SORT__DESCENDING = 'DESCENDING';

    /**
     * An optional start date of the timespan for the transaction history records you're requesting.
     * The startDate must precede the endDate if you specify both dates.
     * To be included in results, the transaction's purchaseDate must be equal to or greater than the startDate.
     */
    protected int $startDate = 0;

    /**
     * An optional end date of the timespan for the transaction history records you're requesting.
     * Choose an endDate that's later than the startDate if you specify both dates.
     * Using an endDate in the future is valid.
     * To be included in results, the transaction's purchaseDate must be less than the endDate.
     */
    protected int $endDate = 0;

    /**
     * An optional filter that indicates the product identifier to include in the transaction history.
     * Your query may specify more than one productID.
     *
     * @var array<string>
     */
    protected array $productId = [];

    /**
     * An optional filter that indicates the product type to include in the transaction history.
     * Your query may specify more than one productType.
     *
     * @var array<string>
     */
    protected array $productType = [];

    /**
     * An optional sort order for the transaction history records.
     * The response sorts the transaction records by their recently modified date.
     * The default value is ASCENDING, so you receive the oldest records first.
     */
    protected string $sort = self::SORT__ASCENDING;

    /**
     * An optional filter that indicates the subscription group identifier to include in the transaction history.
     * Your query may specify more than one subscriptionGroupIdentifier.
     *
     * @var array<int>
     */
    protected array $subscriptionGroupIdentifier = [];

    /**
     * An optional filter that limits the transaction history by the in-app ownership type.
     */
    protected string $inAppOwnershipType = '';

    /**
     * An optional Boolean value that indicates whether the transaction history excludes refunded and revoked transactions.
     * The default value is false.
     */
    protected bool $excludeRevoked = false;
}
