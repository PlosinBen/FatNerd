<?php

namespace App\Support;

class BcMath
{
    protected static $scale = 2;

    public static function scale(int $scale)
    {
        self::$scale = $scale;
    }

    /**
     * @param string|int $num1
     * @param string[]|int[] $nums
     * @return string
     */
    public static function add($num1, ...$nums): string
    {
        return array_reduce(
            $nums,
            fn($current, $num) => bcadd($current, $num, self::$scale),
            $num1
        );
    }

    /**
     * @param string[] $nums
     * @return string
     */
    public static function sum($nums)
    {
        return BcMath::add(...$nums);
    }

    /**
     * @param string|int $num1
     * @param string[]|int[] $nums
     * @return string
     */
    public static function sub($num1, ...$nums): string
    {
        return array_reduce(
            $nums,
            fn($current, $num) => bcsub($current, $num, self::$scale),
            $num1
        );
    }

    /**
     * @param string|int $num1
     * @param string|int $num2
     * @return string
     */
    public static function div($num1, $num2)
    {
        return bcdiv($num1, $num2, self::$scale);
    }

    /**
     * @param string|int $num1
     * @param string|int $num2
     * @return string
     */
    public static function mul($num1, $num2)
    {
        return bcmul($num1, $num2, self::$scale);
    }

    public static function ceil($number)
    {
        if (strpos($number, '.') !== false) {
            if (preg_match("~\.[0]+$~", $number)) return self::round($number, 0);
            if ($number[0] != '-') return self::add($number, 1, 0);
            return bcsub($number, 0, 0);
        }
        return $number;
    }

    public static function floor(string $number)
    {
        if (strpos($number, '.') !== false) {
            if (preg_match("~\.[0]+$~", $number)) return BcMath::round($number, 0);
            if ($number[0] != '-') return bcadd($number, 0, 0);
            return bcsub($number, 1, 0);
        }
        return $number;
    }

    public static function round($number, $precision = 0)
    {
        if (strpos($number, '.') !== false) {
            if ($number[0] != '-') return bcadd($number, '0.' . str_repeat('0', $precision) . '5', $precision);
            return bcsub($number, '0.' . str_repeat('0', $precision) . '5', $precision);
        }
        return $number;
    }

    /**
     * @param string|int $num1
     * @param string|int $num2
     * @return int
     */
    public static function comp($num1, $num2)
    {
        return bccomp($num1, $num2);
    }

    /**
     * 1: num1 > num2
     * 0: equal
     * -1: num1 < num2
     *
     * @param string|int $num1
     * @param string|int $num2
     * @param ?int $scale = null
     * @return int
     */
    public static function compare($num1, $num2, ?int $scale = null)
    {
        return bccomp($num1, $num2, $scale);
    }

    public static function more($num1, $num2, ?int $scale = null)
    {
        return bccomp($num1, $num2, $scale) === 1;
    }

    public static function less($num1, $num2, ?int $scale = null)
    {
        return bccomp($num1, $num2, $scale) === -1;
    }

    public static function equal($num1, $num2, ?int $scale = null)
    {
        return bccomp($num1, $num2, $scale) === 0;
    }
}
