<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;
use function json_decode;
use function json_last_error;
use function property_exists;

abstract class AbstractResponse
{
    const ENVIRONMENT__PRODUCTION = 'Production';
    const ENVIRONMENT__SANDBOX = 'Sandbox';

    protected AbstractRequest $originalRequest;

    protected function __construct(array $properties, AbstractRequest $originalRequest)
    {
        $this->originalRequest = $originalRequest;

        foreach ($properties as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @throws MalformedResponseException
     */
    public static function createFromString(string $string, AbstractRequest $originalRequest): static
    {
        $array = json_decode($string, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new MalformedResponseException('Malformed JWT: Invalid JSON');
        }

        try {
            $response = new static($array, $originalRequest);
        } catch (MalformedJWTException $e) {
            throw new MalformedResponseException('Malformed JWT: ' . $e->getMessage());
        }

        return $response;
    }
}
