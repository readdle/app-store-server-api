<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\GetImageListResponseItem;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static GetImageListResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class GetImageListResponse extends AbstractResponse
{
    /**
     * An array of all image identifiers and their image state.
     *
     * @var array<GetImageListResponseItem>
     */
    protected array $imageIdentifiers = [];

    /**
     * @param array<string, mixed> $properties
     */
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        foreach ($properties['imageIdentifiers'] ?? [] as $rawItem) {
            $this->imageIdentifiers[] = GetImageListResponseItem::createFromRawItem($rawItem);
        }

        unset($properties['imageIdentifiers']);
        parent::__construct($properties, $originalRequest);
    }

    /**
     * Returns an array of all image identifiers and their image state.
     *
     * @return array<GetImageListResponseItem>
     */
    public function getImageIdentifiers(): array
    {
        return $this->imageIdentifiers;
    }
}
