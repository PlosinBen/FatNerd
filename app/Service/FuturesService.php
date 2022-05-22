<?php

namespace App\Service;

use App\Models\Invest\InvestFutures;
use App\Repository\Invest\InvestBalanceRepository;
use App\Repository\Invest\InvestFuturesRepository;
use App\Repository\Invest\InvestHistoryRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FuturesService
{
    public function getList($filter = [])
    {
        return app(InvestFuturesRepository::class)
            ->fetchPagination(['orderBy' => 'period Desc'] + $filter);
    }

    public function get(Carbon $period)
    {
        return app()->make(InvestFuturesRepository::class)
            ->find($period);
    }

    public function createFutures(Carbon $period, int $commitment, int $openInterest, int $profit)
    {
        return app()->make(InvestFuturesRepository::class)
            ->insert(
                $period,
                $commitment,
                $openInterest,
                $profit
            )->refresh();
    }

    protected function createBasicInvestFuturesProfit(Carbon $period)
    {
        $accountHistory = app()->make(InvestHistoryRepository::class)
            ->fetchByPeriod($period)
            ->groupBy('invest_account_id');

        dd(
            $accountHistory
        );
    }

    public function distributeProfit(InvestFutures $investFutures)
    {
        $amountPerQuota = 5000;

        $this->createBasicInvestFuturesProfit($investFutures->periodDate);

        app()->make(InvestHistoryRepository::class)
            ->fetchAccountsComputable($investFutures->periodDate)
            ->map(fn($computable, $investAccountId) => collect([
                'invest_futures_id' => $investFutures->id,
                'invest_account_id' => $investAccountId,
                'computable' => $computable,
                'quota' => floor($computable / $amountPerQuota)
            ]))
            ->pipe(function ($accountsComputed) use (&$investFutures) {
                $prePeriodRealCommitment = app(InvestFuturesRepository::class)
                    ->find($investFutures->periodDate->copy()->subMonth())->real_commitment;

                $netDepositWithdraw = app(InvestHistoryRepository::class)
                    ->calcNetDepositWithdraw($investFutures->periodDate);

                $profitCommitment = $investFutures->real_commitment - $netDepositWithdraw - $prePeriodRealCommitment;

                $totalQuota = $accountsComputed->sum('quota');

                $profit = $investFutures->cover_profit == 0 ? $profitCommitment : min($profitCommitment, $investFutures->cover_profit);

                $profitPerQuota = floor($profit / $totalQuota);

                $investFutures = app(InvestFuturesRepository::class)
                    ->updateProfit(
                        $investFutures,
                        $netDepositWithdraw,
                        $profitCommitment,
                        $profit,
                        $totalQuota,
                        $profitPerQuota
                    );

                return $accountsComputed;
            })
            ->map(fn($accountComputed) => $accountComputed->put('profit', $accountComputed['quota'] * $investFutures->profit_per_quota))
            ->pipe(function ($accountsComputed) use ($investFutures) {
                //calc profit overage
                $adminAccountComputed = $accountsComputed->get(1);

                $adminAccountComputed->put(
                    'profit',
                    $adminAccountComputed->get('profit') +
                    $investFutures->profit -
                    $accountsComputed->sum('profit')
                );


                return $accountsComputed;
            })
            ->pipe(function ($accountsComputed) {
                //create futures profit
                app(InvestBalanceRepository::class)
                    ->create($accountsComputed);

                return $accountsComputed;
            })
            ->each(function (Collection $accountComputed) use ($investFutures) {
                app(InvestHistoryRepository::class)
                    ->insertProfit(
                        $accountComputed->get('invest_account_id'),
                        $investFutures->periodDate,
                        $accountComputed->get('profit')
                    );

                app(InvestHistoryRepository::class)
                    ->updateBalance(
                        $accountComputed->get('invest_account_id'),
                        $investFutures->periodDate
                    );
            });
    }
}
