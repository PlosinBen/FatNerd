<?php

namespace App\Repository\Invest;

use App\Models\Invest\InvestBalance;
use App\Support\BcMath;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InvestBalanceRepository extends \App\Contract\Repository
{
    protected $model = InvestBalance::class;

    public function fetchByAccountPeriod(int $investAccountId, Carbon $period)
    {
        return $this->modelSetFilter([
            'invest_account_id' => $investAccountId,
            'period' => $period->format('Y-m')
        ])->first();
    }

    public function fetchByAccountYear(int $investAccountId, $year)
    {
        return $this->getModelInstance()
            ->investAccountId($investAccountId)
            ->where('period', 'like', "{$year}%")
            ->orderBy('period', 'desc')
            ->get();
    }

    public function fetchSumOfTypeByPeriod(Carbon $period)
    {
        return $this->getModelInstance()
            ->selectRaw("
                SUM(deposit) as deposit,
                SUM(withdraw) as withdraw,
                SUM(profit) as profit,
                SUM(expense) as expense,
                SUM(transfer) as transfer
            ")
            ->period($period)
            ->first();
    }

    /**
     * @param Carbon $period
     * @param Carbon|null $endPeriod
     * @return Model
     */
    public function fetchAccountSumOfTypeByPeriod(Carbon $period, Carbon $endPeriod = null)
    {
        $query = $this->getModelInstance()
            ->selectRaw("
                invest_account_id,
                SUM(deposit) as deposit,
                SUM(withdraw) as withdraw,
                SUM(profit) as profit,
                SUM(expense) as expense,
                SUM(transfer) as transfer
            ")
            ->groupBy('invest_account_id');

        if ($endPeriod === null) {
            $query = $query->period($period);
        } else {
            $query = $query
                ->startPeriod($period)
                ->endPeriod($endPeriod);
        }

        return $query->get();
    }

    public function update(
        int    $investAccountId,
        Carbon $period,
        string $deposit,
        string $withdraw,
        string $profit,
        string $expense,
        string $transfer,
        string $balance = null
    )
    {
        $entity = $this->getModelInstance()
            ->firstOrNew([
                'invest_account_id' => $investAccountId,
                'period' => $period->format('Y-m')
            ]);

        # computable = preBalance + withdraw + transfer
        # computable = Balance - deposit - profit - expense
        $computable = BcMath::sub(
            $balance,
            $deposit,
            $profit,
            $expense
        );

        $quota = 0;
        $numberPerQuota = config('invest.contract.step');

        if (BcMath::more($computable, '0')) {
            $quota = BcMath::floor(
                BcMath::comp($computable, $numberPerQuota) ? BcMath::div($computable, $numberPerQuota) : 1
            );

            if( BcMath::equal($quota, 0) ) {
                $quota = 0.5;
            }
        }

        return $this->updateModel($entity, [
            'deposit' => $deposit,
            'withdraw' => $withdraw,
            'profit' => $profit,
            'expense' => $expense,
            'transfer' => $transfer,
            'balance' => $balance,

            'computable' => $computable,
            'quota' => $quota
        ]);
    }

    /**
     * this use for IDE method hint
     * @return InvestBalance|Model
     */
    protected function getModelInstance()
    {
        return parent::getModelInstance();
    }
}
