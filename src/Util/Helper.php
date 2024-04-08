<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Util;

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
        if (strpos($certificate, "-----BEGIN CERTIFICATE-----\n") !== false) {
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
