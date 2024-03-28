<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

final class HTTPRequestFailed extends AppStoreServerAPIException
{
    private string $responseText;

    public function __construct(string $method, string $url, string $message, int $statusCode = 0, string $responseText = '')
    {
        $this->responseText = $responseText;

        if ($statusCode === 0) {
            parent::__construct("HTTP request [$method $url] failed: $message");
        } else {
            parent::__construct("HTTP request [$method $url] failed with status code $statusCode. Response text is: $responseText", $statusCode);
        }
    }

    public function getResponseText(): string
    {
        return $this->responseText;
    }
}
