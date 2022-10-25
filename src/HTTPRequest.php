<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Readdle\AppStoreServerAPI\Exception\HTTPRequestAborted;
use Readdle\AppStoreServerAPI\Exception\HTTPRequestFailed;
use Readdle\AppStoreServerAPI\Exception\JWTCreationException;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use function file_get_contents;
use function set_error_handler;
use function stream_context_create;
use function trim;

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
                'header' => 'Authorization: Bearer ' . $token,
            ]
        ];

        $url = $request->getURL();

        $errorCode = 0;
        $errorMessage = '';

        $errorHandler = function (int $code, string $error) use ($url, &$errorCode, &$errorMessage): bool {
            $errorPattern =
                '/^file_get_contents\(http(?:s)?:[\/\w\.-]+\): '
                . 'Failed to open stream: HTTP request failed! '
                . 'HTTP\/\d\.\d (\d+) ([\w\s]+)$/';

            if (preg_match($errorPattern, trim($error), $m)) {
                $errorCode = (int) $m[1];
                $errorMessage = $m[2];
            }

            return true;
        };

        $previousErrorHandler = set_error_handler($errorHandler);

        $response = file_get_contents(
            $url,
            false,
            stream_context_create($options)
        );

        set_error_handler($previousErrorHandler);

        if ($response === false) {
            throw new HTTPRequestFailed($errorMessage, $errorCode);
        }

        return $response;
    }
}
