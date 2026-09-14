<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestQueryParams;

final class UploadImageQueryParams extends AbstractRequestQueryParams
{
    const IMAGE_SIZE__FULL_SIZE = 'FULL_SIZE';
    const IMAGE_SIZE__BULLET_POINT = 'BULLET_POINT';

    /**
     * The size of the image you upload.
     * The default value is FULL_SIZE.
     */
    protected string $imageSize = self::IMAGE_SIZE__FULL_SIZE;
}
