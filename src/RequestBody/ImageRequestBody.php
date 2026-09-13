<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

final class ImageRequestBody extends AbstractRequestBody
{
    const CONTENT_TYPE__PNG = 'image/png';

    /**
     * The raw binary contents of the PNG image file to upload.
     */
    protected string $imageData;

    protected array $requiredFields = ['imageData'];

    public function getContentType(): string
    {
        return self::CONTENT_TYPE__PNG;
    }

    public function getEncodedContent(): string
    {
        return $this->imageData;
    }
}
