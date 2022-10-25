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

    private Key $key;
    private Payload $payload;

    protected array $defaultQueryParams = [];
    protected array $queryParams = [];
    protected array $urlParams = ['baseUrl' => ''];

    public function __construct(Key $key, Payload $payload, array $queryParams = [])
    {
        $this->key = $key;
        $this->payload = $payload;

        foreach ($queryParams as $key => $value) {
            if (!array_key_exists($key, $this->defaultQueryParams)) {
                continue;
            }

            if ($this->defaultQueryParams[$key] === $value) {
                continue;
            }

            $this->queryParams[$key] = $value;
        }
    }

    public function setURLParams(array $urlParams): void
    {
        $this->urlParams = array_merge($this->urlParams, $urlParams);
    }

    abstract public function getHTTPMethod(): string;

    public function getURL(): string
    {
        return preg_replace_callback(
            '/{\w+}/',
            fn ($match) => $this->urlParams[trim($match[0], '{}')] ?? $match[0],
            $this->getURLPattern()
        );
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
