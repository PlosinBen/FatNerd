<?php

namespace App\Data;

use App\Contract\Enum;
use App\Support\BcMath;

/**
 * @method static all()
 *
 * @method static InvestHistoryType deposit()
 * @method static InvestHistoryType withdraw()
 * @method static InvestHistoryType profit()
 * @method static InvestHistoryType expense()
 * @method static InvestHistoryType transfer()
 */
class InvestHistoryType extends Enum
{
    protected $options = [
        'deposit',
        'withdraw',
        'profit',
        'expense',
        'transfer'
    ];

    protected $sort = [
        'deposit' => 0,
        'withdraw' => 0,
        'profit' => 100,
        'expense' => 200,
        'transfer' => 0
    ];

    protected $negative = [
        'withdraw',
        'expense',
        'transfer'
    ];

    protected function options(): array
    {
        return $this->options;
    }

    /**
     * @param string $amount
     * @return string
     */
    public function getSign(string $amount)
    {
        if (in_array($this->value, $this->negative)) {
            return BcMath::mul($amount, '-1');
        }

        return $amount;
    }

    /**
     * @param $value
     * @return int
     */
    public static function getForceSortNumber($value)
    {
        switch ($value) {
            case "profit":
                return 100;
            case "expense":
                return 200;
            default:
                return 0;
        }
    }
}
