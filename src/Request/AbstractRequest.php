<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Request;

use Readdle\AppStoreServerAPI\Key;
use Readdle\AppStoreServerAPI\Payload;
use Readdle\AppStoreServerAPI\RequestBody\AbstractRequestBody;
use Readdle\AppStoreServerAPI\RequestQueryParams\AbstractRequestQueryParams;

use function array_merge;
use function preg_replace_callback;
use function trim;

abstract class AbstractRequest
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PUT = 'PUT';

    protected Key $key;
    protected Payload $payload;
    protected ?AbstractRequestQueryParams $queryParams;
    protected ?AbstractRequestBody $body;

    /** @var array<string, mixed> */
    protected array $urlVars = ['baseUrl' => ''];

    public function __construct(
        Key $key,
        Payload $payload,
        ?AbstractRequestQueryParams $queryParams,
        ?AbstractRequestBody $body
    ) {
        $this->key = $key;
        $this->payload = $payload;
        $this->queryParams = $queryParams;
        $this->body = $body;
    }

    public function getKey(): Key
    {
        return $this->key;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    public function getQueryParams(): ?AbstractRequestQueryParams
    {
        return $this->queryParams;
    }

    public function getBody(): ?AbstractRequestBody
    {
        return $this->body;
    }

    /**
     * @return array<string, mixed>
     */
    public function getURLVars(): array
    {
        return $this->urlVars;
    }

    /**
     * @param array<string, mixed> $urlVars
     */
    public function setURLVars(array $urlVars): void
    {
        $this->urlVars = array_merge($this->urlVars, $urlVars);
    }

    public function composeURL(): string
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
            fn ($match) => $this->urlVars[trim($match[0], '{}')] ?? $match[0],
            $this->getURLPattern()
        ) . $tail;
    }

    abstract public function getHTTPMethod(): string;

    abstract protected function getURLPattern(): string;
}
