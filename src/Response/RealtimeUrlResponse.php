<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static RealtimeUrlResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class RealtimeUrlResponse extends AbstractResponse
{
    /**
     * A string that contains the URL you provided for your Get Retention Message endpoint.
     */
    protected string $realtimeURL;

    /**
     * Returns the URL you provided for your Get Retention Message endpoint.
     */
    public function getRealtimeURL(): string
    {
        return $this->realtimeURL;
    }
}
