<?php

declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Exception;

final class HTTPRequestFailed extends AppStoreServerAPIException
{

    public int $statusCode;

    public string $responseText;

    public function __construct(string $method, string $url, string $message, int $code = 0)
    {

        $this->statusCode = $code;

        $this->responseText = $message;

        if ($code === 0) {
            parent::__construct("HTTP request [$method $url] failed: $message");
        } else {
            parent::__construct("HTTP request [$method $url] failed with status code $code. Response text is: $message", $code);
        }
    }

    /**
     * Get the HTTP status code
     *
     * @return integer
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the response text
     *
     * @return string
     */
    public function getResponseText(): string
    {
        return $this->responseText;
    }
}
