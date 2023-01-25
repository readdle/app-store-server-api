<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Request;

use Readdle\AppStoreServerAPI\Key;
use Readdle\AppStoreServerAPI\Payload;
use function array_key_exists;
use function array_merge;
use function preg_replace_callback;
use function trim;

abstract class AbstractRequest
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';

    protected array $urlParams = ['baseUrl' => ''];

    public function __construct(
        protected Key $key,
        protected Payload $payload,
        protected ?AbstractQueryParams $queryParams = null
    ) {
    }

    public function setURLParams(array $urlParams): void
    {
        $this->urlParams = array_merge($this->urlParams, $urlParams);
    }

    abstract public function getHTTPMethod(): string;

    public function getURL(): string
    {
        $tail = '';

        if ($this->queryParams !== null) {
            $queryString = $this->queryParams->getQueryString();

            if (!empty($queryString)) {
                $tail = '?' . $queryString;
            }
        }

        return preg_replace_callback(
            '/{\w+}/',
            fn ($match) => $this->urlParams[trim($match[0], '{}')] ?? $match[0],
            $this->getURLPattern()
        ) . $tail;
    }

    public function getKey(): Key
    {
        return $this->key;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    abstract protected function getURLPattern(): string;
}
