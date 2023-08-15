<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\NotificationHistoryResponseItem;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;

final class CheckTestNotificationResponse extends AbstractResponse
{
    private NotificationHistoryResponseItem $notificationHistoryResponseItem;

    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        $this->notificationHistoryResponseItem = NotificationHistoryResponseItem::createFromRawItem($properties);
        $properties = [];
        parent::__construct($properties, $originalRequest);
    }

    public function getNotificationHistoryResponseItem(): NotificationHistoryResponseItem
    {
        return $this->notificationHistoryResponseItem;
    }
}
