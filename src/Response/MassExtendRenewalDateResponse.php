<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static MassExtendRenewalDateResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class MassExtendRenewalDateResponse extends AbstractResponse
{
    /**
     * A string that contains the UUID that identifies the subscription-renewal-date extension request.
     */
    protected string $requestIdentifier;

    /**
     * returns a string that contains the UUID that identifies the subscription-renewal-date extension request.
     */
    public function getRequestIdentifier(): string
    {
        return $this->requestIdentifier;
    }
}
