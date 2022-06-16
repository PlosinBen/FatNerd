<?php

namespace App\Service\Invest\Contract;

use App\Support\BcMath;

class Standard
{
    protected $costThreshold = 1;

    protected $costInterval = [
        5 => 500,
        10 => 1000,
        15 => 1500,
        20 => 2000,
        25 => 2500,
        30 => 3000,
        35 => 3500,
        40 => 4000,
        45 => 4500,
        50 => 5000
    ];

    protected $commissionInterval = [
        5 => 0.02,
        10 => 0.04,
        15 => 0.06,
        20 => 0.08,
        25 => 0.1,
        30 => 0.12,
        35 => 0.14,
        40 => 0.16,
        45 => 0.18,
        50 => 0.2
    ];

    protected $profit;

    protected $balance;

    protected $message = [];

    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;

        return $this;
    }

    public function calculateExpense()
    {
        $this->message = [];

        $expense = BcMath::add(
            $this->getCommission(),
            $this->getCost()
        );

        $this->message[] = "= {$expense}";

        return [
            $expense,
            $this->getNote()
        ];
    }

    protected function getNote()
    {
        return implode(' ', $this->message);
    }

    protected function getCost()
    {
        $balance = BcMath::div($this->balance, 10000);

        $cost = 0;
        if (BcMath::more($balance, $this->costThreshold)) {
            $costInterval = collect($this->costInterval)
                ->filter(fn($cost, $interval) => BcMath::more($balance, $interval));

            $cost = $costInterval->count()
                ? $costInterval->last()
                : $this->costInterval[array_key_first($this->costInterval)];
        }

        $this->message[] = "+{$cost}";

        return $cost;
    }

    protected function getCommission()
    {
        $rate = $this->getCommissionRate();

        $commission = BcMath::mul(
            $this->profit,
            $rate
        );

        $this->message[] = "{$this->profit}*{$rate}";

        return BcMath::floor($commission);
    }

    protected function getCommissionRate()
    {
        $balance = BcMath::div($this->balance, 10000);

        $commissionInterval = collect($this->commissionInterval)
            ->filter(fn($comm, $interval) => BcMath::more($balance, $interval));

        return $commissionInterval->count()
            ? $commissionInterval->last()
            : $this->getDefaultCommissionRate();
    }

    protected function getDefaultCommissionRate()
    {
        return $this->commissionInterval[array_key_first($this->commissionInterval)];
    }
}
