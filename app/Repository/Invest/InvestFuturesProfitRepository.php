<?php

namespace App\Repository\Invest;

use App\Models\Invest\InvestFuturesProfit;
use App\Models\Invest\InvestFutures;
use Illuminate\Contracts\Support\Arrayable;

class InvestFuturesProfitRepository extends \App\Contract\Repository
{
    protected $model = InvestFuturesProfit::class;

    /**
     * @param InvestFutures $investFutures
     * @param $accountsProfitData
     * @return mixed
     */
    public function create($accountsProfitData)
    {
        if ($accountsProfitData instanceof Arrayable) {
            $accountsProfitData = $accountsProfitData->toArray();
        }

//        foreach ($accountsProfitData as $index => $row) {
//            $accountsProfitData[$index]['invest_futures_id'] = $investFutures->id;
//        }

        return InvestFuturesProfit::upsert($accountsProfitData, [
            'invest_futures_id',
            'invest_account_id'
        ], [
            'computable',
            'quota',
            'profit',
        ]);
    }
}
