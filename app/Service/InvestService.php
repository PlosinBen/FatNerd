<?php

namespace App\Service;

use App\Data\InvestHistoryType;
use App\Models\Invest\InvestHistory;
use App\Repository\Invest\InvestBalanceRepository;
use App\Repository\Invest\InvestHistoryRepository;
use App\Support\BcMath;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class InvestService
{
    protected InvestHistoryRepository $investHistoryRepository;

    public function __construct(InvestHistoryRepository $investHistoryRepository)
    {
        $this->investHistoryRepository = $investHistoryRepository;
    }

    public function getListByYear(int $investAccountId, int $year)
    {
        return $this->investHistoryRepository
            ->fetchByAccount($investAccountId, $year);
    }

    public function getYears(int $investAccountId): Collection
    {
        return $this->investHistoryRepository
            ->fetchOccurredYears($investAccountId)
            ->sortDesc()
            ->values();
    }

    public function create(int $investAccountId, Carbon $occurredAt, InvestHistoryType $investHistoryType, string $amount, ?string $note)
    {
        if (!method_exists($this, $specialMethod = parseCameCase("create_{$investHistoryType->get()}_history"))) {
            $specialMethod = "createBasicHistory";
        }

        call_user_func_array([$this, $specialMethod], [
            $investAccountId,
            $occurredAt,
            $investHistoryType,
            $amount,
            $note
        ]);

        $this->updateBalance($investAccountId, $occurredAt);
    }

    protected function createBasicHistory(int $investAccountId, Carbon $occurredAt, InvestHistoryType $investHistoryType, string $amount, ?string $note)
    {
        $this->investHistoryRepository->insert(
            $investAccountId,
            $occurredAt,
            $investHistoryType,
            $investHistoryType->getSign($amount),
            $note ?? ''
        );
    }

    protected function createExpenseHistory(int $investAccountId, Carbon $occurredAt, InvestHistoryType $investHistoryType, string $amount, ?string $note)
    {
        // 費用
        // user $investAccountId 新增費用(負數)
        $this->investHistoryRepository->insert(
            $investAccountId,
            $occurredAt,
            $investHistoryType,
            $investHistoryType->getSign($amount),
            $note ?? ''
        );

        // user 1 新增費用(正數)
        $this->investHistoryRepository->insert(
            1,
            $occurredAt,
            $investHistoryType,
            $amount,
            "From {$investAccountId} Expense"
        );
    }

    protected function createTransferHistory(int $investAccountId, Carbon $occurredAt, InvestHistoryType $investHistoryType, string $amount, ?string $note)
    {
        // 出金轉存
        // user 1 新增出金轉存(正數)
        $this->investHistoryRepository->insert(
            1,
            $occurredAt,
            $investHistoryType,
            $amount,
            "From {$investAccountId} Transfer"
        );

        // user $investAccountId 新增出金(負數)
        $this->investHistoryRepository->insert(
            $investAccountId,
            $occurredAt,
            $type = InvestHistoryType::withdraw(),
            $type->getSign($amount),
            $note
        );
    }

    protected function updateBalance(int $investAccountId, Carbon $period)
    {
        $lastBalance = $this->investHistoryRepository->fetchLastBalanceOfPeriod(
            $investAccountId,
            $period->copy()->subMonth()
        );

        $lastBalance = $this->investHistoryRepository
            ->fetchByAccountPeriod($investAccountId, $period)
            ->sortBy(function (InvestHistory $investHistory) {
                $prefix = InvestHistoryType::getForceSortNumber($investHistory->type);
                $occurredDay = $investHistory->occurred_at->format('d');
                $createdDay = $investHistory->created_at->format('d');

                Log::info("{$investHistory->id}: {$prefix}{$occurredDay}{$createdDay}");

                return "{$prefix}{$occurredDay}{$createdDay}";
            }, SORT_NUMERIC)
            ->values()
            ->reduce(
                fn($preBalance, InvestHistory $investHistory, $index) => $this->investHistoryRepository->updateBalance(
                    $investHistory,
                    BcMath::add($preBalance, $investHistory->amount),
                    $index + 1
                )->balance,
                $lastBalance
            );

        #update monthly balance
        #$lastBalance
    }

    protected function calcFuturesProfit(int $investAccountId, Carbon $period)
    {
        $InvestHistoryRepository = app()->make(InvestHistoryRepository::class);

        $typeAmounts = $InvestHistoryRepository
            ->fetchByAccountPeriod($investAccountId, $period)
            ->groupBy('type')
            ->map(fn(Collection $groupInvestHistories) => BcMath::sum($groupInvestHistories->pluck('amount')->toArray()));


        return app()->make(InvestBalanceRepository::class)
            ->update(
                $investAccountId,
                $period,
                $typeAmounts->get('deposit'),
                $typeAmounts->get('withdraw'),
                $typeAmounts->get('profit'),
                $typeAmounts->get('expense'),
                $typeAmounts->get('transfer'),
            );
    }
}
