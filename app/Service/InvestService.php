<?php

namespace App\Service;

use App\Models\Invest\InvestFutures;
use App\Repository\Invest\InvestFuturesProfitRepository;
use App\Repository\Invest\InvestHistoryRepository;
use App\Repository\Invest\InvestFuturesRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InvestService
{
    protected InvestHistoryRepository $investHistoryRepository;

    public function __construct(InvestHistoryRepository $investHistoryRepository)
    {
        $this->investHistoryRepository = $investHistoryRepository;
    }

    public function getList()
    {
        return $this->investHistoryRepository
            ->fetchPagination([]);
    }

    public function getFuturesList()
    {
        return app(InvestFuturesRepository::class)
            ->fetchPagination([]);
    }

    public function distributeProfit(InvestFutures $investFutures)
    {
        $amountPerQuota = 5000;

        app(InvestHistoryRepository::class)
            ->fetchAccountsComputable($investFutures->period)
            ->map(fn($computable, $investAccountId) => collect([
                'period' => $investFutures->period,
                'invest_account_id' => $investAccountId,
                'computable' => $computable,
                'quota' => floor($computable / $amountPerQuota)
            ]))
            ->pipe(function ($accountsComputed) use (&$investFutures) {
                $prePeriodRealCommitment = app(InvestFuturesRepository::class)
                    ->find($investFutures->period->copy()->subMonth())->real_commitment;

                $netDepositWithdraw = app(InvestHistoryRepository::class)
                    ->calcNetDepositWithdraw($investFutures->period);

                $profitCommitment = $investFutures->real_commitment - $netDepositWithdraw - $prePeriodRealCommitment;

                $totalQuota = $accountsComputed->sum('quota');

                $profit = $investFutures->cover_profit === 0 ? $profitCommitment : min($profitCommitment, $investFutures->cover_profit);

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
                app(InvestFuturesProfitRepository::class)
                    ->create($accountsComputed);

                return $accountsComputed;
            })
            ->each(function (Collection $accountComputed) {
                app(InvestHistoryRepository::class)
                    ->insertProfit(
                        $accountComputed->get('invest_account_id'),
                        $accountComputed->get('period'),
                        $accountComputed->get('profit')
                    );

                app(InvestHistoryRepository::class)
                    ->updateBalance(
                        $accountComputed->get('invest_account_id'),
                        $accountComputed->get('period')
                    );
            });
    }

    public function createFutures(Carbon $period, int $commitment, int $openInterest, int $profit)
    {
        return app(InvestFuturesRepository::class)
            ->insert(
                $period,
                $commitment,
                $openInterest,
                $profit
            );
    }
}
