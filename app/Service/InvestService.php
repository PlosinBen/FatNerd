<?php

namespace App\Service;

use App\Repository\Invest\InvestDetailRepository;
use App\Repository\Invest\InvestHistoryRepository;
use App\Repository\Invest\InvestStatementFuturesRepository;
use Carbon\Carbon;

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
        return app(InvestStatementFuturesRepository::class)
            ->fetchPagination([]);
    }

    public function createFutures(Carbon $period, int $commitment, int $openInterest, int $profit)
    {
        $realCommitment = $commitment - $openInterest;

        $netDepositWithdraw = app(InvestDetailRepository::class)
            ->calcNetDepositWithdraw($period);

        $investStatementFuturesRepository  = app(InvestStatementFuturesRepository::class);

        $prePeriodFutures = $investStatementFuturesRepository
            ->find($period->copy()->subMonth());

        $profitCommitment = $realCommitment - $netDepositWithdraw - $prePeriodFutures->real_commitment;

        return $investStatementFuturesRepository->insert(
            $period,
            $commitment,
            $openInterest,
            $profit,
            $realCommitment,
            $netDepositWithdraw,
            $profitCommitment,
        );
    }
}
