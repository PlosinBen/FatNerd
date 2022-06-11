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

        if (BcMath::comp($computable, '0')) {
            $quota = BcMath::floor(
                BcMath::comp($computable, $numberPerQuota) ? BcMath::div($computable, $numberPerQuota) : 1
            );
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
