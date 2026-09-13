<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;

final class GetImageListResponseItem implements JsonSerializable
{
    /**
     * The image is awaiting approval.
     */
    const IMAGE_STATE__PENDING = 'PENDING';

    /**
     * The image is approved.
     */
    const IMAGE_STATE__APPROVED = 'APPROVED';

    /**
     * The image is rejected.
     */
    const IMAGE_STATE__REJECTED = 'REJECTED';

    /**
     * The identifier of the image.
     */
    private string $imageIdentifier;

    /**
     * The size of the image.
     * Default: FULL_SIZE
     */
    private string $imageSize;

    /**
     * The current state of the image.
     *
     * @see self::IMAGE_STATE__PENDING
     * @see self::IMAGE_STATE__APPROVED
     * @see self::IMAGE_STATE__REJECTED
     */
    private string $imageState;

    private function __construct(string $imageIdentifier, string $imageSize, string $imageState)
    {
        $this->imageIdentifier = $imageIdentifier;
        $this->imageSize = $imageSize;
        $this->imageState = $imageState;
    }

    /**
     * @param array<string, mixed> $rawItem
     */
    public static function createFromRawItem(array $rawItem): self
    {
        return new self($rawItem['imageIdentifier'], $rawItem['imageSize'], $rawItem['imageState']);
    }

    /**
     * Returns the identifier of the image.
     */
    public function getImageIdentifier(): string
    {
        return $this->imageIdentifier;
    }

    /**
     * Returns the size of the image.
     */
    public function getImageSize(): string
    {
        return $this->imageSize;
    }

    /**
     * Returns the current state of the image.
     */
    public function getImageState(): string
    {
        return $this->imageState;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
