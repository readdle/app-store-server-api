<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Request;

final class GetTestNotificationStatusRequest extends AbstractRequest
{
    public function getHTTPMethod(): string
    {
        return self::HTTP_METHOD_GET;
    }

    protected function getURLPattern(): string
    {
        return '{baseUrl}/v1/notifications/test/{testNotificationToken}';
    }
}
