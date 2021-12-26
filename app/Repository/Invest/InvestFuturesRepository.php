<?php

namespace App\Repository\Invest;

use App\Models\Invest\InvestFutures;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

class InvestFuturesRepository extends \App\Contract\Repository
{
    protected $model = InvestFutures::class;

    public function find($id): ?Model
    {
        if ($id instanceof Carbon) {
            $id = $id->format('Y-m');
        }

        return parent::find($id);
    }

    /**
     * @param Carbon $period
     * @param int $commitment
     * @param int $openInterest
     * @param int $coverProfit
     * @return InvestFutures
     */
    public function insert(
        Carbon $period,
        int    $commitment,
        int    $openInterest,
        int    $coverProfit
    ): InvestFutures
    {
        return InvestFutures::updateOrCreate([
            'period' => $period->format('Y-m')
        ], [
            'commitment' => $commitment,
            'open_interest' => $openInterest,
            'cover_profit' => $coverProfit,
            'real_commitment' => $commitment - $openInterest,
//            'net_deposit_withdraw' => $netDepositWithdraw,
//            'profit_commitment' => $profitCommitment,
//            'distribution' => min($profitCommitment, $profit)
        ]);
    }

    /**
     * @param InvestFutures $investFutures
     * @param int $netDepositWithdraw
     * @param int $totalQuota
     * @return InvestFutures|Model|null
     */
    public function updateProfit(
        InvestFutures $investFutures,
        int           $netDepositWithdraw,
        int           $profitCommitment,
        int           $profit,
        int           $totalQuota,
        int           $profitPerQuota
    )
    {
        return $this->updateModel($investFutures, [
            'net_deposit_withdraw' => $netDepositWithdraw,
            'commitment_profit' => $profitCommitment,
            'profit' => $profit,
            'total_quota' => $totalQuota,
            'profit_per_quota' => $profitPerQuota
        ]);
    }


    /**
     * @param InvestFutures $futures
     * @param array|Arrayable $profits
     * @return void
     */
    public function bindProfits(InvestFutures $futures, $profits)
    {
        return $futures
            ->InvestFuturesProfits()
            ->updateOrCreate([], []);
    }
}
