<?php

namespace App\Repository\Invest;

use App\Contract\Repository;
use App\Models\Invest\InvestProfit;
use Carbon\Carbon;

class InvestProfitRepository extends Repository
{
    protected $model = InvestProfit::class;

    public function insert(
        int $investAccountId,
        Carbon $period,
        string $type,
        string $computable,
        int $quota,
        string $profit
    )
    {
        $this->getModelInstance()
            ->updateOrCreate([
                'period' => $period->format('Y-m'),
                'invest_account_id' => $investAccountId,
                'type' => $type
            ], [
                'computable' => $computable,
                'quota' => $quota,
                'profit' => $profit
            ]);
    }
}
