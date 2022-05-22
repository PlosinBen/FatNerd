<?php

namespace App\Service;

use App\Data\InvestHistoryType;
use App\Models\Invest\InvestAccount;
use App\Models\Invest\InvestFutures;
use App\Repository\Invest\InvestBalanceRepository;
use App\Repository\Invest\InvestFuturesRepository;
use App\Repository\Invest\InvestHistoryRepository;
use App\Support\BcMath;
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

    public function createFutures(Carbon $period, int $commitment, int $openInterest, ?int $profit, int $deposit = 0, int $withdraw = 0)
    {
        return app()->make(InvestFuturesRepository::class)
            ->insert(
                $period,
                $commitment,
                $openInterest,
                $profit,
                $deposit,
                $withdraw
            );
    }

    public function distributeProfit(InvestFutures $investFutures)
    {
        $investService = app()->make(InvestService::class);
        $investFuturesRepository = app()->make(InvestFuturesRepository::class);

        $investAccountQuota = $investService
            ->getComputableBalance($investFutures->periodDate->copy())
            ->pluck('quota', 'invest_account_id');

        $totalQuota = $investAccountQuota->sum();

        $investFutures = $investFuturesRepository->updateQuota($investFutures, $totalQuota);

        $investAccountProfit = $investAccountQuota
            ->forget(1)
            ->map(fn($quota) => BcMath::mul($quota, $investFutures->profit_per_quota));

        $investAccountProfit
            ->put(
                1,
                BcMath::sub(
                    $investFutures->profit,
                    BcMath::add(...$investAccountProfit)
                )
            );

        $investAccountProfit->each(function ($accountProfit, $investAccountId) use ($investService, $investFutures) {
            $investService->create(
                $investAccountId,
                $investFutures->periodDate->copy(),
                InvestHistoryType::profit(),
                $accountProfit
            );
        });
    }
}
