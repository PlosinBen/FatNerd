<?php

namespace App\Repository\Invest;

use App\Models\Invest\InvestStatementFutures;
use Carbon\Carbon;

class InvestStatementFuturesRepository extends \App\Contract\Repository
{
    protected $model = InvestStatementFutures::class;

    public function insert(
        Carbon $period,
        int    $commitment,
        int    $openInterest,
        int    $profit,
        int    $realCommitment,
        int    $netDepositWithdraw,
        int    $profitCommitment
    )
    {
        return $this->insertModel([
            'period' => $period->toDateString(),
            'commitment' => $commitment,
            'open_interest' => $openInterest,
            'profit' => $profit,
            'real_commitment' => $realCommitment,
            'net_deposit_withdraw' => $netDepositWithdraw,
            'profit_commitment' => $profitCommitment,
            'distribution' => min($profitCommitment, $profit)
        ]);
    }
}
