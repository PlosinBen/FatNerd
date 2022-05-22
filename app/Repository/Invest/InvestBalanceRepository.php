<?php

namespace App\Repository\Invest;

use App\Data\InvestHistoryType;
use App\Models\Invest\InvestBalance;
use App\Models\Invest\InvestFutures;
use App\Models\Invest\InvestHistory;
use App\Support\BcMath;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

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

    public function update(
        int    $investAccountId,
        Carbon $period,
        string $deposit,
        string $withdraw,
        string $profit,
        string $expense,
        string $transfer
    )
    {
        $prePeriodEntity = $this->fetchByAccountPeriod(
            $investAccountId,
            $period->copy()->subMonth()
        );

        $prePeriodBalance = optional($prePeriodEntity)->balance ?? '0';

        $entity = $this->getModelInstance()
            ->firstOrNew([
                'invest_account_id' => $investAccountId,
                'period' => $period->format('Y-m')
            ]);

        $balance = BcMath::add(
            $prePeriodBalance,
            $deposit,
            $withdraw,
            $profit,
            $expense,
            $transfer
        );

        $computable = BcMath::add(
            $prePeriodBalance,
            $withdraw,
            $transfer,
        );

        $quota = BcMath::floor(
            BcMath::div($computable, '5000')
        );

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
}
