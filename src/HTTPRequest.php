<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Exception;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\JWTCreationException;
use Readdle\AppStoreServerAPI\Exception\UnimplementedContentTypeException;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use Readdle\AppStoreServerAPI\Util\JWT;

use function file_get_contents;
use function preg_match;
use function reset;
use function stream_context_create;

final class HTTPRequest
{
    /**
     * @throws Exception
     * @throws HTTPRequestFailed
     * @throws HTTPRequestAborted
     * @throws UnimplementedContentTypeException
     */
    public static function performRequest(AbstractRequest $request): string
    {
        try {
            $token = JWT::createFrom($request->getKey(), $request->getPayload());
        } catch (JWTCreationException|Exception $e) {
            throw new HTTPRequestAborted('Authorization token could not be generated', $e);
        }

        $httpMethod = $request->getHTTPMethod();

        $options = [
            'http' => [
                'method' => $httpMethod,
                'header' => "Authorization: Bearer $token",
                'ignore_errors' => true,
            ]
        ];

        if (in_array($httpMethod, [AbstractRequest::HTTP_METHOD_POST, AbstractRequest::HTTP_METHOD_PUT])) {
            $body = $request->getBody();

            if ($body) {
                $options['http']['header'] .= "\r\nContent-Type: {$body->getContentType()}";
                $options['http']['content'] = $body->getEncodedContent();
            }
        }

        $url = $request->composeURL();

        $response = file_get_contents(
            $url,
            false,
            stream_context_create($options)
        );

        /** @noinspection PhpNullIsNotCompatibleWithParameterInspection */
        $statusLine = reset($http_response_header);

        if (!preg_match('/^HTTP\/\d\.\d (?<statusCode>\d+) (?<reasonPhrase>[^\n\r]+)$/', $statusLine, $matches)) {
            throw new HTTPRequestFailed($httpMethod, $url, "Wrong Status-Line: $statusLine");
        }

        $statusCode = (int) $matches['statusCode'];

        if ($statusCode >= 400) {
            throw new HTTPRequestFailed($httpMethod, $url, $response, $statusCode);
        }

        return $response;
    }
}
