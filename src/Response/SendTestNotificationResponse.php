<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

final class SendTestNotificationResponse extends AbstractResponse
{
    protected string $testNotificationToken;

    public function getTestNotificationToken(): string
    {
        return $this->testNotificationToken;
    }
}
