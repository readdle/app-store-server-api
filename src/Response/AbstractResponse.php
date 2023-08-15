<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Response;

use Readdle\AppStoreServerAPI\Exception\MalformedResponseException;
use Readdle\AppStoreServerAPI\Request\AbstractRequest;

use function json_decode;
use function json_last_error;
use function property_exists;

use const JSON_ERROR_NONE;

/** @phpstan-consistent-constructor */
abstract class AbstractResponse
{
    protected AbstractRequest $originalRequest;

    /**
     * @param array<string, mixed> $properties
     */
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
     * @return AbstractResponse|PageableResponse
     * @throws MalformedResponseException
     */
    public static function createFromString(string $string, AbstractRequest $originalRequest): AbstractResponse
    {
        $array = json_decode($string, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new MalformedResponseException('Invalid JSON');
        }

        return new static($array, $originalRequest);
    }
}
