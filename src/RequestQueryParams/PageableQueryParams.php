<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestQueryParams;

abstract class PageableQueryParams extends AbstractRequestQueryParams
{
    /**
     * A token you provide to get the next set of items. All responses include a revision token.
     *
     * NOTE: For requests that use the revision token, include the same query parameters from the initial request.
     * Use the revision token from the previous response.
     * The revision token is required in all requests except the initial one.
     */
    protected string $revision = '';

    public function forRevision(string $revision): PageableQueryParams
    {
        $newQueryParams = clone $this;
        $newQueryParams->revision = $revision;
        return $newQueryParams;
    }
}
