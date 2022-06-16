<?php

namespace App\Service\Invest\Contract;

use App\Support\BcMath;

class Friend extends Standard
{
    protected $costInterval = [
        5 => 500,
        10 => 1000,
        15 => 1500,
        20 => 2000,
        25 => 2500,
        30 => 3000
    ];

    protected $commissionInterval = [
        5 => 0.02,
        10 => 0.03,
        15 => 0.04,
        20 => 0.05,
        25 => 0.06,
        30 => 0.07,
        35 => 0.08,
        40 => 0.09,
        45 => 0.1
    ];

    public function calculateExpense()
    {
        //2020-12 熊 損益計算錯誤 - 保留數字
        if (BcMath::equal($this->balance, 32212) && BcMath::equal($this->profit, 2212)) {
            $this->profit = 2152;
        }
        //2020-12 Emma 損益計算錯誤 - 保留數字
        if (BcMath::equal($this->balance, 21060) && BcMath::equal($this->profit, 1060)) {
            $this->profit = 1020;
        }

        return parent::calculateExpense();
    }
}
