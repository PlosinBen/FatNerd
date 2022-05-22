<?php

namespace App\Support;

class BcMath
{
    public static function scale(?int $scale = null)
    {
        bcscale($scale);
    }

    public static function add(string $num1, string ...$nums): string
    {
        return array_reduce(
            $nums,
            fn($current, $num) => bcadd($current, $num),
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

    public static function sub(string $num1, string $num2): string
    {
        return bcsub($num1, $num2);
    }

    public static function div(string $num1, string $num2)
    {
        return bcdiv($num1, $num2);
    }

    public static function mul(string $num1, string $num2)
    {
        return bcmul($num1, $num2);
    }

    public static function ceil($number)
    {
        if (strpos($number, '.') !== false) {
            if (preg_match("~\.[0]+$~", $number)) return BcMath::round($number, 0);
            if ($number[0] != '-') return bcadd($number, 1, 0);
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
}
