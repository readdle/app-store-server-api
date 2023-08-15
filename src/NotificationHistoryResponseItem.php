<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;
use Readdle\AppStoreServerAPI\Exception\AppStoreServerNotificationException;

final class NotificationHistoryResponseItem implements JsonSerializable
{
    /**
     * An array of information the App Store server records for its attempts to send a notification to your server.
     * The maximum number of entries in the array is six.
     *
     * @var array<SendAttemptItem>
     */
    protected array $sendAttempts;

    /**
     * The cryptographically signed payload, in JSON Web Signature (JWS) format, containing the original response body
     * of a version 2 notification.
     */
    protected string $signedPayload;

    /**
     * The original response body of a version 2 notification.
     */
    protected ?ResponseBodyV2 $responseBodyV2 = null;

    /**
     * @param array<SendAttemptItem> $sendAttempts
     */
    private function __construct(array $sendAttempts, string $signedPayload)
    {
        $this->sendAttempts = $sendAttempts;
        $this->signedPayload = $signedPayload;

        try {
            $this->responseBodyV2 = ResponseBodyV2::createFromRawNotification("{\"signedPayload\":\"$signedPayload\"}");
        } catch (AppStoreServerNotificationException $e) {
            // nothing to do with this hypothetical situation
        }
    }

    /**
     * @param array<string, mixed> $rawItem
     */
    public static function createFromRawItem(array $rawItem): self
    {
        $sendAttempts = [];

        foreach ($rawItem['sendAttempts'] as $rawSendAttempt) {
            $sendAttempts[] = SendAttemptItem::createFromRawSendAttempt($rawSendAttempt);
        }

        return new self($sendAttempts, $rawItem['signedPayload']);
    }

    /**
     * Returns an array of information the App Store server records for its attempts to send a notification to your
     * server.
     * The maximum number of entries in the array is six.
     *
     * @return array<SendAttemptItem>
     */
    public function getSendAttempts(): array
    {
        return $this->sendAttempts;
    }

    /**
     * Returns the cryptographically signed payload, in JSON Web Signature (JWS) format, containing the original
     * response body of a version 2 notification.
     */
    public function getSignedPayload(): string
    {
        return $this->signedPayload;
    }

    /**
     * Returns the original response body of a version 2 notification.
     */
    public function getResponseBodyV2(): ?ResponseBodyV2
    {
        return $this->responseBodyV2;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
