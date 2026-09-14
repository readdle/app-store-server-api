<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Request;

final class DeleteMessageRequest extends AbstractRequest
{
    public function getHTTPMethod(): string
    {
        return self::HTTP_METHOD_DELETE;
    }

    protected function getURLPattern(): string
    {
        return '{baseUrl}/v1/messaging/message/{messageIdentifier}';
    }
}
