<?php

namespace App\Service\Invest\Contract;

use App\Support\BcMath;

class Specially extends Standard
{
    protected $commissionInterval = [
        5 => 0.01,
        15 => 0.02,
        25 => 0.03,
        35 => 0.04,
        45 => 0.05
    ];

    public function calculateExpense()
    {
        //2020-12 wu 損益計算錯誤 - 保留數字
        if (BcMath::equal($this->balance, 41552) && BcMath::equal($this->profit, 1442)) {
            $this->profit = 1362;
        }

        return parent::calculateExpense();
    }

    public function getCost()
    {
        return 0;
    }

    protected function getDefaultCommissionRate()
    {
        if (BcMath::more($this->balance, 5000)) {
            return parent::getDefaultCommissionRate();
        }

        return 0;
    }
}
