<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Util;

use Exception;

final class ASN1SequenceOfInteger
{
    const ASN1_INTEGER = 0x02;
    const ASN1_SEQUENCE_IDENTIFIER = 0x10;
    const ASN1_CONSTRUCTED = 0x20;

    const ASN1_BIG_INT_MAX_FIRST_BYTE = 0x7F;

    /**
     * @throws Exception
     */
    public static function toHex(string $asn1): string
    {
        $position = 0;

        if (ord($asn1[$position++]) !== (self::ASN1_CONSTRUCTED | self::ASN1_SEQUENCE_IDENTIFIER)) {
            throw new Exception('ASN1SequenceOfIntegerReader error: not a sequence');
        }

        if (ord($asn1[$position++]) !== strlen($asn1) - 2) {
            throw new Exception('ASN1SequenceOfIntegerReader error: incorrect sequence length');
        }

        $hexParts = [];
        $asn1Length = strlen($asn1);

        do {
            if (ord($asn1[$position++]) !== self::ASN1_INTEGER) {
                throw new Exception('ASN1SequenceOfIntegerReader error: entry is not an integer');
            }

            $length = ord($asn1[$position++]);

            if (ord($asn1[$position]) === 0) {
                $position++;
                $length--;
            }

            $bytesArray = str_split(substr($asn1, $position, $length));

            if (ord($bytesArray[0]) === 0 && ord($bytesArray[1]) > self::ASN1_BIG_INT_MAX_FIRST_BYTE) {
                array_shift($bytesArray);
            }

            $hexParts[] = join(array_map(
                fn (string $chr) => str_pad(dechex(ord($chr)), 2, '0', STR_PAD_LEFT),
                $bytesArray
            ));

            $position += $length;
        } while ($position < $asn1Length);

        $maxLength = array_reduce($hexParts, fn (int $carry, string $item) => max($carry, strlen($item)), 0);
        return join(array_map(fn (string $hex) => str_pad($hex, $maxLength, '0', STR_PAD_LEFT), $hexParts));
    }

    /**
     * @throws Exception
     */
    public static function fromHex(string $hexSignature): string
    {
        $length = strlen($hexSignature);

        if ($length % 2) {
            throw new Exception('Invalid signature length');
        }

        $hexParts = str_split($hexSignature, $length / 2);

        foreach ($hexParts as &$hexPart) {
            $firstByteHex = substr($hexPart, 0, 2);

            if (hexdec($firstByteHex) > self::ASN1_BIG_INT_MAX_FIRST_BYTE) {
                $hexPart = '00' . $hexPart;
            } else {
                while ($firstByteHex === '00' && hexdec(substr($hexPart, 2, 2)) <= self::ASN1_BIG_INT_MAX_FIRST_BYTE) {
                    $hexPart = substr($hexPart, 2);
                    $firstByteHex = substr($hexPart, 0, 2);
                }
            }
        }

        $encodedIntegers = join(array_map(
            fn (string $hexPart) => join([
                chr(self::ASN1_INTEGER),
                chr(strlen($hexPart) / 2),
                join(array_map(fn (string $hex) => chr(hexdec($hex)), str_split($hexPart, 2))),
            ]),
            $hexParts
        ));

        return join([
            chr(self::ASN1_CONSTRUCTED | self::ASN1_SEQUENCE_IDENTIFIER),
            chr(strlen($encodedIntegers)),
            $encodedIntegers,
        ]);
    }
}
