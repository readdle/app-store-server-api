<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\RequestQueryParams;

use Readdle\AppStoreServerAPI\Request\AbstractRequestParamsBag;

use function array_map;
use function array_merge;
use function join;
use function rawurlencode;

abstract class AbstractRequestQueryParams extends AbstractRequestParamsBag
{
    public function getQueryString(): string
    {
        $queryStringParams = [];

        foreach ($this->collectProps() as $propName => $value) {
            if (is_array($value)) {
                $queryStringParams = array_merge(
                    $queryStringParams,
                    array_map(fn ($v) => $propName . '=' . rawurlencode($v), $value)
                );
            } else {
                $queryStringParams[] = $propName . '=' . rawurlencode($value);
            }
        }

        return join('&', $queryStringParams);
    }
}
