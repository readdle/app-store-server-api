<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestQueryParams;

final class GetAllSubscriptionStatusesQueryParams extends AbstractRequestQueryParams
{
    /**
     * An optional filter that indicates the status of subscriptions to include in the response.
     * Your query may specify more than one status query parameter.
     *
     * @var array<int>
     */
    protected array $status = [];
}
