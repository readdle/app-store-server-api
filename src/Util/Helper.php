<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Util;

use Generator;
use function array_key_exists;
use function base64_decode;
use function base64_encode;
use function chunk_split;
use function str_repeat;
use function str_replace;
use function strlen;
use function strtr;
use function trim;

final class Helper
{
    /**
     * @param array<string, mixed> $input
     * @param array<string, array<string>> $typeCastMap
     */
    public static function arrayTypeCastGenerator(array $input, array $typeCastMap): Generator
    {
        foreach ($typeCastMap as $type => $keys) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $input)) {
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

    public static function base64Encode(string $input): string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    public static function base64Decode(string $input): string
    {
        $remainder = strlen($input) % 4;

        if ($remainder) {
            $input .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function formatPEM(string $certificate): string
    {
        if (strrpos($certificate, "-----BEGIN CERTIFICATE-----\n") !== false) {
            return $certificate;
        }

        return join("\n", [
            "-----BEGIN CERTIFICATE-----",
            $certificate,
            "-----END CERTIFICATE-----"
        ]);
    }

    public static function toPEM(string $binary): string
    {
        return trim(chunk_split(base64_encode($binary), 64));
    }
}
