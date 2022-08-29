<?php

namespace App\Service;

use App\Data\InvestHistoryType;
use App\Models\Invest\InvestBalance;
use App\Models\Invest\InvestFutures;
use App\Repository\Invest\InvestFuturesRepository;
use App\Service\Invest\ExpenseService;
use App\Support\BcMath;
use Carbon\Carbon;

class FuturesService
{
    public function getList($filter = [], int $perPage = 24)
    {
        return app(InvestFuturesRepository::class)
            ->perPage($perPage)
            ->fetchPagination(['orderBy' => 'period Desc'] + $filter);
    }

    public function getAll($filter = [])
    {
        return app(InvestFuturesRepository::class)
            ->fetch($filter);
    }

    public function get(Carbon $period)
    {
        return app(InvestFuturesRepository::class)
            ->find($period);
    }

    public function createFutures(Carbon $period, int $commitment, int $openInterest, ?int $profit, ?int $deposit = null, ?int $withdraw = null)
    {
        if ($deposit === null || $withdraw === null) {
            $summary = app(InvestService::class)->getBalanceTypeSummary($period);

            $deposit = $deposit ?? $summary->deposit;
            $withdraw = $withdraw ?? $summary->withdraw;
        }

        return app(InvestFuturesRepository::class)
            ->insert(
                $period,
                $commitment,
                $openInterest,
                $profit,
                $deposit ?? 0,
                $withdraw ?? 0
            );
    }

    public function distributeExpense(Carbon $period)
    {
        if (!in_array($period->month, [6, 12])) {
            return $this;
        }

        /**
         * @var $investService InvestService
         */
        $investService = app(InvestService::class);

        $expenseAbles = $investService
            ->getComputeExceptionBalance(
                $period->copy()->subMonths(5),
                $period->copy()
            );

        if ($expenseAbles->count() === 0) {
            return;
        }

        $expenseAbles->each(function (InvestBalance $investBalance) use ($investService, $period) {
//            echo "{$period->toDateString()} - {$investBalance->InvestAccount->alias} - {$investBalance->balance} - {$investBalance->profit}";
            [$expense, $note] = app()->make(ExpenseService::class)
                ->get($investBalance->InvestAccount->contract)
                ->setBalance($investBalance->balance)
                ->setProfit($investBalance->profit)
                ->calculateExpense();

//            echo " - {$expense}";

            if (BcMath::more($expense, 0)) {
                $investService
                    ->create(
                        $investBalance->invest_account_id,
                        $period->copy()->lastOfMonth(),
                        InvestHistoryType::expense(),
                        $expense,
                        $note
                    );
            }

//            echo PHP_EOL;
        });

        $investService->updateBalance(1, $period);

        return $this;
    }

    public function distributeProfit(InvestFutures $investFutures)
    {
        if ($investFutures->periodDate->lessThan('2018-10')) {
            return $this;
        }

        $investService = app(InvestService::class);
        $investFuturesRepository = app(InvestFuturesRepository::class);

        $investAccountQuota = $investService
            ->getComputableBalance($investFutures->periodDate->copy())
            ->pluck('quota', 'invest_account_id');

        $totalQuota = $investAccountQuota->sum();

        $investFutures = $investFuturesRepository->updateQuota($investFutures, $totalQuota);

        $investAccountProfit = $investAccountQuota
            ->forget(1)
            ->map(fn($quota) => BcMath::floor(
                BcMath::mul($quota, $investFutures->profit_per_quota)
            ));

        $investAccountProfit
            ->put(
                1,
                BcMath::sub(
                    $investFutures->commitment_profit,
                    BcMath::add(...$investAccountProfit)
                )
            );

        $investAccountProfit->each(function ($accountProfit, $investAccountId) use ($investService, $investFutures, $investAccountQuota) {
            $investService->create(
                $investAccountId,
                $investFutures->periodDate->copy(),
                InvestHistoryType::profit(),
                $accountProfit
            );
        });

        return $this;
    }
}
