<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\GetMessageListResponseItem;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static GetMessageListResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class GetMessageListResponse extends AbstractResponse
{
    /**
     * An array of all message identifiers and their message states.
     *
     * @var array<GetMessageListResponseItem>
     */
    protected array $messageIdentifiers = [];

    /**
     * @param array<string, mixed> $properties
     */
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        foreach ($properties['messageIdentifiers'] ?? [] as $rawItem) {
            $this->messageIdentifiers[] = GetMessageListResponseItem::createFromRawItem($rawItem);
        }

        unset($properties['messageIdentifiers']);
        parent::__construct($properties, $originalRequest);
    }

    /**
     * Returns an array of all message identifiers and their message states.
     *
     * @return array<GetMessageListResponseItem>
     */
    public function getMessageIdentifiers(): array
    {
        return $this->messageIdentifiers;
    }
}
