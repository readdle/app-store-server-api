<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

final class UnimplementedContentTypeException extends AppStoreServerAPIException
{
    public function __construct(string $contentType)
    {
        parent::__construct("Unimplemented content type $contentType");
    }
}
