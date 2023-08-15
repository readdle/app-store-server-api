<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Request;

class GetAllSubscriptionStatusesRequest extends AbstractRequest
{
    public function getHTTPMethod(): string
    {
        return self::HTTP_METHOD_GET;
    }

    protected function getURLPattern(): string
    {
        return '{baseUrl}/v1/subscriptions/{transactionId}';
    }
}
