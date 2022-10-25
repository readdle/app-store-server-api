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
use function str_starts_with;
use function strlen;
use function strtr;
use function trim;

final class Helper
{
    private const PEM_PREFIX = "-----BEGIN CERTIFICATE-----\n";
    private const PEM_POSTFIX = "\n-----END CERTIFICATE-----";

    public static function arrayTypeCastGenerator(array $input, array $typeCastMap): Generator
    {
        foreach ($typeCastMap as $type => $keys) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $input)) {
                    continue;
                }

                yield $key => match ($type) {
                    'int' => (int) $input[$key],
                    'bool' => (bool) $input[$key],
                    'float' => (float) $input[$key],
                    'string' => (string) $input[$key],

                    default => null,
                };
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

    public static function checkAndFormatPEM(string $certificate): string
    {
        if (str_starts_with($certificate, self::PEM_PREFIX)) {
            return $certificate;
        }

        return self::PEM_PREFIX . $certificate . self::PEM_POSTFIX;
    }

    public static function DER2PEM(string $der): string
    {
        return trim(chunk_split(base64_encode($der), 64));
    }
}
