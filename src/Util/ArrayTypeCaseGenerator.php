<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Util;

use Generator;

final class ArrayTypeCaseGenerator
{
    /**
     * @param array<string, mixed> $input
     * @param array<string, array<string>> $typeCastMap
     */
    public function __invoke(array $input, array $typeCastMap): Generator
    {
        foreach ($typeCastMap as $type => $keys) {
            foreach ($keys as $key) {
                $isNullable = $key[0] === '?';

                if ($isNullable) {
                    $key = substr($key, 1);
                }

                if (!array_key_exists($key, $input)) {
                    continue;
                }

                if ($input[$key] === null && $isNullable) {
                    yield $key => null;

                    continue;
                }

                switch ($type) {
                    case 'int':
                        yield $key => (int) $input[$key];

                        break;

                    case 'bool':
                        yield $key => (bool) $input[$key];

                        break;

                    case 'float':
                        yield $key => (float) $input[$key];

                        break;

                    case 'string':
                        yield $key => (string) $input[$key];

                        break;

                    default:
                        yield $key => null;
                }
            }
        }
    }
}
