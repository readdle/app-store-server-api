<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Request\AbstractRequest;

/**
 * @method static SendTestNotificationResponse createFromString(string $string, AbstractRequest $originalRequest)
 */
final class SendTestNotificationResponse extends AbstractResponse
{
    /**
     * The test notification token that uniquely identifies the notification test that
     * App Store Server Notifications sends to your server.
     */
    protected string $testNotificationToken;

    /**
     * Returns the test notification token that uniquely identifies the notification test that
     * App Store Server Notifications sends to your server.
     */
    public function getTestNotificationToken(): string
    {
        return $this->testNotificationToken;
    }
}
