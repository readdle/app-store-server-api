<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Generator;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Exception\UnimplementedContentTypeException;
use Readdle\AppStoreServerAPI\HTTPRequest;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\RequestQueryParams\PageableQueryParams;

use function get_class;
use function is_subclass_of;

/**
 * @method static PageableResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
abstract class PageableResponse extends AbstractResponse
{
    /**
     * A Boolean value that indicates whether the App Store has more items than it returns in this response.
     * If the value is true, use the revision token to request the next set of items.
     */
    protected bool $hasMore;

    /**
     * A token you use in a query to request the next set of items
     */
    protected string $revision;

    /**
     * Reference to the request which represents the next page of response.
     */
    protected ?PageableResponse $nextPage = null;

    /**
     * An array of items.
     *
     * @var array<int, mixed>
     */
    protected array $items = [];

    /**
     * @throws HTTPRequestAborted
     * @throws HTTPRequestFailed
     * @throws MalformedResponseException
     * @throws UnimplementedContentTypeException
     */
    protected function getItems(): Generator
    {
        $page = $this;

        do {
            foreach ($page->items as $item) {
                yield $item;
            }

            if ($page->hasMore) {
                $queryParams = $page->originalRequest->getQueryParams();

                if (is_subclass_of($queryParams, PageableQueryParams::class)) {
                    $class = get_class($page->originalRequest);
                    $nextRequest = new $class(
                        $page->originalRequest->getKey(),
                        $page->originalRequest->getPayload(),
                        $queryParams->forRevision($page->revision),
                        $page->originalRequest->getBody()
                    );

                    $nextRequest->setURLVars($page->originalRequest->getURLVars());
                    $responseText = HTTPRequest::performRequest($nextRequest);
                    $page->nextPage = static::createFromString($responseText, $nextRequest);
                }
            }

            $page = $page->nextPage;
        } while ($page);
    }
}
