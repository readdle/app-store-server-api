<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\JWTCreationException;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use function file_get_contents;
use function preg_match;
use function reset;
use function stream_context_create;

final class HTTPRequest
{
    /**
     * @throws HTTPRequestFailed
     * @throws HTTPRequestAborted
     */
    public static function performRequest(AbstractRequest $request): string
    {
        try {
            $token = JWT::createFrom($request->getKey(), $request->getPayload());
        } catch (JWTCreationException $e) {
            throw new HTTPRequestAborted('Authorization token could not be generated: ' . $e->getMessage());
        }

        $options = [
            'http' => [
                'method' => $request->getHTTPMethod(),
                'header' => "Authorization: Bearer $token",
                'ignore_errors' => true,
            ]
        ];

        $url = $request->getURL();

        $response = file_get_contents(
            $url,
            false,
            stream_context_create($options)
        );

        /** @noinspection PhpNullIsNotCompatibleWithParameterInspection */
        $statusLine = reset($http_response_header);

        if (!preg_match('/^HTTP\/\d\.\d (?<statusCode>\d+) (?<reasonPhrase>[^\n\r]+)$/', $statusLine, $matches)) {
            throw new HTTPRequestFailed("Wrong Status-Line: $statusLine", -1);
        }

        $statusCode = (int) $matches['statusCode'];

        if ($statusCode !== 200) {
            throw new HTTPRequestFailed($response, $statusCode);
        }

        return $response;
    }
}
