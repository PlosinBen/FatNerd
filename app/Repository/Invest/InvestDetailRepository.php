<?php

namespace App\Repository\Invest;

use App\Models\Invest\InvestDetail;
use Carbon\Carbon;

class InvestDetailRepository extends \App\Contract\Repository
{
    protected $model = InvestDetail::class;

    public function calcNetDepositWithdraw(Carbon $period)
    {
        $data = $this->getModelInstance()
            ->where('occurred_at', 'LIKE', $period->format('Y-m%'))
            ->whereIn('type', ['deposit', 'withdraw'])
            ->groupBy('type')
            ->selectRaw('type, SUM(amount) as amount')
            ->get()
            ->pluck('amount', 'type');

        return $data->get('deposit', 0) - $data->get('withdraw', 0);
    }
}
