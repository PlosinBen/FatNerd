<?php

namespace App\Repository\Invest;

use App\Models\Invest\InvestFuturesProfit;
use App\Models\Invest\InvestFutures;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class InvestFuturesProfitRepository extends \App\Contract\Repository
{
    protected $model = InvestFuturesProfit::class;

    public function insert(
        InvestFutures $futures,
        int           $investAccountId,
        int           $computable,
        int           $quota,
        int           $profit,
        string        $note
    )
    {
        return InvestFuturesProfit::updateOrCreate([
            'period' => $futures->period,
            'invest_account_id' => $investAccountId
        ], [
            'computable' => $computable,
            'quota' => $quota,
            'profit' => $profit,
        ]);
    }

    /**
     * @param array|Arrayable $futuresData
     * @return void
     */
    public function create($futuresData)
    {
        if ($futuresData instanceof Arrayable) {
            $futuresData = $futuresData->toArray();
        }

        foreach ($futuresData as $index => $row) {
            if (!$row['period'] instanceof Carbon) {
                $row['period'] = Carbon::parse($row['period']);
            }

            $futuresData[$index]['period'] = $row['period']->format('Y-m');
        }

        return InvestFuturesProfit::upsert($futuresData, [
            'period',
            'invest_account_id'
        ], [
            'computable',
            'quota',
            'profit',
        ]);
    }
}
