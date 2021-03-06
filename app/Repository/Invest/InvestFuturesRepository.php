<?php

namespace App\Repository\Invest;

use App\Models\Invest\InvestFutures;
use App\Support\BcMath;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

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
     * @param int $deposit
     * @param int $withdraw
     * @return InvestFutures
     */
    public function insert(
        Carbon $period,
        int    $commitment,
        int    $openInterest,
        ?int   $coverProfit,
        int    $deposit,
        int    $withdraw
    ): InvestFutures
    {
        $prePeriodInvestFutures = $this->getModelInstance()
            ->period($period->copy()->subMonth())
            ->first();

        $prePeriodRealCommitment = optional($prePeriodInvestFutures)->real_commitment ?? 0;

        $netDepositWithDraw = BcMath::add($deposit, $withdraw);

        return InvestFutures::updateOrCreate([
            'period' => $period->format('Y-m')
        ], [
            'commitment' => $commitment,
            'open_interest' => $openInterest,
            'cover_profit' => $coverProfit,
            'deposit' => $deposit,
            'withdraw' => $withdraw,
            'real_commitment' => $realCommitment = BcMath::sub($commitment, $openInterest),
//            'net_deposit_withdraw' => $netDepositWithdraw,
            'commitment_profit' => $profitCommitment = BcMath::sub($realCommitment, $netDepositWithDraw, $prePeriodRealCommitment),
            'profit' => $coverProfit === null ? $profitCommitment : min($profitCommitment, $coverProfit)
        ]);
    }

    /**
     * @param InvestFutures $investFutures
     * @param numeric $quota
     * @return InvestFutures|Model|null
     */
    public function updateQuota(InvestFutures $investFutures, $quota)
    {
        return $this->updateModel($investFutures, [
            'total_quota' => $quota,
            'profit_per_quota' => BcMath::floor(
                BcMath::div($investFutures->profit, $quota)
            )
        ]);
    }

    /**
     * @return InvestFutures|Model
     */
    protected function getModelInstance()
    {
        return parent::getModelInstance();
    }
}
