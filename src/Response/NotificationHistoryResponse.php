<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Generator;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Exception\UnimplementedContentTypeException;
use Readdle\AppStoreServerAPI\NotificationHistoryResponseItem;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static NotificationHistoryResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class NotificationHistoryResponse extends PageableResponse
{
    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        foreach ($properties['notificationHistory'] as $rawResponseItem) {
            $this->items[] = NotificationHistoryResponseItem::createFromRawItem($rawResponseItem);
        }

        if (!empty($properties['paginationToken'])) {
            $properties['revision'] = $properties['paginationToken'];
        }

        unset($properties['notificationHistory'], $properties['paginationToken']);
        parent::__construct($properties, $originalRequest);
    }

    /**
     * Returns a Generator which iterates over an array of App Store Server Notifications history records.
     *
     * @return Generator<NotificationHistoryResponseItem>
     *
     * @throws MalformedResponseException
     * @throws HTTPRequestAborted
     * @throws HTTPRequestFailed
     * @throws UnimplementedContentTypeException
     */
    public function getNotificationHistory(): Generator
    {
        return $this->getItems();
    }
}
