<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;

final class GetMessageListResponseItem implements JsonSerializable
{
    /**
     * The message is awaiting approval.
     */
    const MESSAGE_STATE__PENDING = 'PENDING';

    /**
     * The message is approved.
     */
    const MESSAGE_STATE__APPROVED = 'APPROVED';

    /**
     * The message is rejected.
     */
    const MESSAGE_STATE__REJECTED = 'REJECTED';

    /**
     * The identifier of the message.
     */
    private string $messageIdentifier;

    /**
     * The current state of the message.
     *
     * @see self::MESSAGE_STATE__PENDING
     * @see self::MESSAGE_STATE__APPROVED
     * @see self::MESSAGE_STATE__REJECTED
     */
    private string $messageState;

    private function __construct(string $messageIdentifier, string $messageState)
    {
        $this->messageIdentifier = $messageIdentifier;
        $this->messageState = $messageState;
    }

    /**
     * @param array<string, mixed> $rawItem
     */
    public static function createFromRawItem(array $rawItem): self
    {
        return new self($rawItem['messageIdentifier'], $rawItem['messageState']);
    }

    /**
     * Returns the identifier of the message.
     */
    public function getMessageIdentifier(): string
    {
        return $this->messageIdentifier;
    }

    /**
     * Returns the current state of the message.
     */
    public function getMessageState(): string
    {
        return $this->messageState;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
