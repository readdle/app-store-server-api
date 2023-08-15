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
     * @return array<string>
     *
     * @throws Exception
     */
    public static function read(string $asn1): array
    {
        // the first byte is an identifier, 0x20 means "Constructed", 0x10 means "Sequence"
        if (ord($asn1[0]) !== (self::ASN1_CONSTRUCTED | self::ASN1_SEQUENCE_IDENTIFIER)) {
            throw new Exception('ASN1SequenceOfIntegerReader error: not a sequence');
        }

        // the second byte is a length of the whole sequence, should be equal to the length of the rest of the string
        if (ord($asn1[1]) !== strlen($asn1) - 2) {
            throw new Exception('ASN1SequenceOfIntegerReader error: incorrect sequence length');
        }

        $integers = [];
        $position = 2;
        $asn1Length = strlen($asn1);

        do {
            // the first byte is an identifier, 0x02 means "Integer"
            if (ord($asn1[$position]) !== self::ASN1_INTEGER) {
                throw new Exception('ASN1SequenceOfIntegerReader error: entry is not an integer');
            }

            // the second byte is a length of an integer value
            $intLength = ord($asn1[$position + 1]);
            $integer = '0';

            for ($i = $position + 2; $i < $position + $intLength + 2; $i++) {
                $integer = bcadd(bcmul($integer, '256'), (string) ord($asn1[$i]));
            }

            $integers[] = $integer;
            $position += $intLength + 2;
        } while ($position < $asn1Length);

        return $integers;
    }

    /**
     * @param array<array<int>> $intArrays
     */
    public static function create(array $intArrays): string
    {
        array_walk($intArrays, function (array &$intArray) {
            if ($intArray[0] > self::ASN1_BIG_INT_MAX_FIRST_BYTE) {
                array_unshift($intArray, 0);
            }
        });

        $encodedIntegers = join(array_map(
            fn (array $intArray) =>
                chr(self::ASN1_INTEGER)
                . chr(count($intArray))
                . join(array_map(fn (int $int) => chr($int), $intArray))
            ,
            $intArrays
        ));

        return
            chr(self::ASN1_CONSTRUCTED | self::ASN1_SEQUENCE_IDENTIFIER)
            . chr(strlen($encodedIntegers))
            . $encodedIntegers
        ;
    }
}
