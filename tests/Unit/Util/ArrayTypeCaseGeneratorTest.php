<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Tests\Unit\Util;

use PHPUnit\Framework\TestCase;
use Readdle\AppStoreServerAPI\Util\ArrayTypeCaseGenerator;

final class ArrayTypeCaseGeneratorTest extends TestCase
{
    public function testArrayTypeCaseGeneratorNullables(): void
    {
        $testTypeCaseMap = [
            'int' => [
                'notNullableInt', '?nullableInt'
            ],
            'bool' => [
                'notNullableBool', '?nullableBool'
            ],
            'float' => [
                'notNullableFloat', '?nullableFloat',
            ],
            'string' => [
                'notNullableString', '?nullableString',
            ],
        ];

        $testInput = [
            'notNullableInt' => '1',
            'nullableInt' => null,
            'notNullableBool' => 1,
            'nullableBool' => null,
            'notNullableFloat' => '1.1',
            'nullableFloat' => null,
            'notNullableString' => 's',
            'nullableString' => null,
        ];
        $testOutput = [];
        $testTypeCaster = (new ArrayTypeCaseGenerator())($testInput, $testTypeCaseMap);

        foreach ($testTypeCaster as $prop => $value) {
            $testOutput[$prop] = $value;
        }

        $this->assertSame([
            'notNullableInt' => 1,
            'nullableInt' => null,
            'notNullableBool' => true,
            'nullableBool' => null,
            'notNullableFloat' => 1.1,
            'nullableFloat' => null,
            'notNullableString' => 's',
            'nullableString' => null,
        ], $testOutput);
    }

    public function testArrayTypeCaseGeneratorNotNullables(): void
    {
        $testTypeCaseMap = [
            'int' => [
                'notNullableInt', 'nullableInt'
            ],
            'bool' => [
                'notNullableBool', 'nullableBool'
            ],
            'float' => [
                'notNullableFloat', 'nullableFloat',
            ],
            'string' => [
                'notNullableString', 'nullableString',
            ],
        ];

        $testInput = [
            'notNullableInt' => '1',
            'nullableInt' => null,
            'notNullableBool' => 1,
            'nullableBool' => null,
            'notNullableFloat' => '1.1',
            'nullableFloat' => null,
            'notNullableString' => 's',
            'nullableString' => null,
        ];
        $testOutput = [];
        $testTypeCaster = (new ArrayTypeCaseGenerator())($testInput, $testTypeCaseMap);

        foreach ($testTypeCaster as $prop => $value) {
            $testOutput[$prop] = $value;
        }

        $this->assertSame([
            'notNullableInt' => 1,
            'nullableInt' => 0,
            'notNullableBool' => true,
            'nullableBool' => false,
            'notNullableFloat' => 1.1,
            'nullableFloat' => 0.0,
            'notNullableString' => 's',
            'nullableString' => '',
        ], $testOutput);
    }
}
