<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Request;

final class SetAppAccountTokenRequest extends AbstractRequest
{
    public function getHTTPMethod(): string
    {
        return self::HTTP_METHOD_PUT;
    }

    protected function getURLPattern(): string
    {
        return '{baseUrl}/v1/transactions/{transactionId}/appAccountToken';
    }
}
