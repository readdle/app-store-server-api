<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestBody;

use Readdle\AppStoreServerAPI\Exception\UnimplementedContentTypeException;
use Readdle\AppStoreServerAPI\Request\AbstractRequestParamsBag;

abstract class AbstractRequestBody extends AbstractRequestParamsBag
{
    const CONTENT_TYPE__JSON = 'application/json';

    public function getContentType(): string
    {
        return self::CONTENT_TYPE__JSON;
    }

    /**
     * @throws UnimplementedContentTypeException
     */
    public function getEncodedContent(): string
    {
        switch ($this->getContentType()) {
            case self::CONTENT_TYPE__JSON:
                return json_encode($this->collectProps());

            default:
                throw new UnimplementedContentTypeException($this->getContentType());
        }
    }
}
