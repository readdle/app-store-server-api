<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use Exception;

final class Math
{
    private const MATH_LIB_BCMATH = 'bcmath';
    private const MATH_LIB_GMP = 'gmp';
    private const MATH_LIB_NONE = 'none';

    private static string $mathLib;

    private static function getMathLib(): string
    {
        if (!isset(self::$mathLib)) {
            switch (true) {
                case extension_loaded(self::MATH_LIB_BCMATH):
                    self::$mathLib = self::MATH_LIB_BCMATH;
                    break;

                case extension_loaded(self::MATH_LIB_GMP):
                    self::$mathLib = self::MATH_LIB_GMP;
                    break;

                default:
                    self::$mathLib = self::MATH_LIB_NONE;
            }
        }

        return self::$mathLib;
    }

    /**
     * @throws Exception
     */
    public static function add(string $num1, string $num2): string
    {
        switch (self::getMathLib()) {
            case self::MATH_LIB_BCMATH:
                return bcadd($num1, $num2);

            case self::MATH_LIB_GMP:
                return gmp_strval(gmp_add($num1, $num2));

            default:
                throw new Exception('Math::add() native implementation is not available... yet');
        }
    }

    /**
     * @throws Exception
     */
    public static function div(string $num1, string $num2): string
    {
        switch (self::getMathLib()) {
            case self::MATH_LIB_BCMATH:
                return bcdiv($num1, $num2);

            case self::MATH_LIB_GMP:
                return gmp_strval(gmp_div($num1, $num2));

            default:
                throw new Exception('Math::div() native implementation is not available... yet');
        }
    }

    /**
     * @throws Exception
     */
    public static function mul(string $num1, string $num2): string
    {
        switch (self::getMathLib()) {
            case self::MATH_LIB_BCMATH:
                return bcmul($num1, $num2);

            case self::MATH_LIB_GMP:
                return gmp_strval(gmp_mul($num1, $num2));

            default:
                throw new Exception('Math::mul() native implementation is not available... yet');
        }
    }

    /**
     * @throws Exception
     */
    public static function mod(string $num1, string $num2): string
    {
        switch (self::getMathLib()) {
            case self::MATH_LIB_BCMATH:
                return bcmod($num1, $num2);

            case self::MATH_LIB_GMP:
                return gmp_strval(gmp_mod($num1, $num2));

            default:
                throw new Exception('Math::mod() native implementation is not available... yet');
        }
    }
}
