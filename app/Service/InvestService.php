<?php

namespace App\Service;

use App\Data\InvestHistoryType;
use App\Models\Invest\InvestAccount;
use App\Models\Invest\InvestHistory;
use App\Repository\Invest\InvestAccountRepository;
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
        /**
         * @var InvestBalanceRepository $investBalanceRepository
         */
        $investBalanceRepository = app(InvestBalanceRepository::class);

        return $investBalanceRepository
            ->fetchByAccountYear(
                $investAccountId,
                $year
            );
    }

    public function getRecordsByYear(int $investAccountId, int $year)
    {
        return $this->investHistoryRepository
            ->fetchByAccountYear($investAccountId, $year);
    }

    public function getYears(int $investAccountId): Collection
    {
        return $this->investHistoryRepository
            ->fetchOccurredYears($investAccountId)
            ->sortDesc()
            ->values();
    }

    public function getInvestAccounts()
    {
        return app(InvestAccountRepository::class)
            ->fetch();
    }

    public function getBalanceTypeSummary(Carbon $period)
    {
        return app(InvestBalanceRepository::class)
            ->fetchTotalAmountOfType();
    }

    public function create(int $investAccountId, Carbon $occurredAt, InvestHistoryType $investHistoryType, string $amount, ?string $note = null)
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

        $this->updateHistorySerialNumber($investAccountId, $occurredAt);

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

    protected function createProfitHistory(int $investAccountId, Carbon $occurredAt, InvestHistoryType $investHistoryType, string $amount, ?string $note)
    {
        $this->investHistoryRepository
            ->insertProfit(
                $investAccountId,
                $occurredAt,
                $amount,
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

//    protected function calc

    protected function updateHistorySerialNumber(int $investAccountId, Carbon $period)
    {
        $this->investHistoryRepository
            ->fetchByAccountPeriod($investAccountId, $period)
            ->sortBy(function (InvestHistory $investHistory) {
                $prefix = InvestHistoryType::getForceSortNumber($investHistory->type);
                $occurredDay = $investHistory->occurred_at->format('d');
                $createdDay = $investHistory->created_at->format('d');

                Log::info("{$investHistory->id}: {$prefix}{$occurredDay}{$createdDay}");

                return "{$prefix}{$occurredDay}{$createdDay}";
            }, SORT_NUMERIC)
            ->values()
            ->each(fn(InvestHistory $investHistory, $index) => $this->investHistoryRepository->updateSerialNumber(
                $investHistory,
                $index + 1
            ));
    }

    public function updateBalance(int $investAccountId, Carbon $period)
    {
        /**
         * @var InvestBalanceRepository $investBalanceRepository
         */
        $investBalanceRepository = app(InvestBalanceRepository::class);

        $prePeriodInvestBalance = $investBalanceRepository
            ->fetchByAccountPeriod($investAccountId, $period->copy()->subMonth());

        $investTypeAmount = $this->investHistoryRepository
            ->fetchByAccountPeriod($investAccountId, $period->copy())
            ->groupBy('type')
            ->map(function ($investHistoryTypeGroup) {
                return $investHistoryTypeGroup->reduce(
                    fn($current, InvestHistory $investHistory) => BcMath::add($current, $investHistory->amount),
                    '0'
                );
            });

        $investTypeAmount->put(
            'balance',
            BcMath::add(
                optional($prePeriodInvestBalance)->balance ?? 0,
                ...$investTypeAmount->values()
            )
        );

        $needSave = $investTypeAmount
            ->reduce(
                fn(bool $current, $amount, $type) => $current || BcMath::comp($amount, '0') !== 0,
                true
            );

        if (!$needSave) {
            return null;
        }

        return $investBalanceRepository->update(
            $investAccountId,
            $period->copy(),
            $investTypeAmount->get('deposit', '0'),
            $investTypeAmount->get('withdraw', '0'),
            $investTypeAmount->get('profit', '0'),
            $investTypeAmount->get('expense', '0'),
            $investTypeAmount->get('transfer', '0'),
            $investTypeAmount->get('balance', '0')
        );
    }

    /**
     * @param Carbon $period
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getComputableBalance(Carbon $period)
    {
        return $this->getInvestAccounts()
            #fetch invest account balance
            ->map(function (InvestAccount $investAccount) use ($period) {
                return $this->updateBalance($investAccount->id, $period->copy());
            })
            #filter null(all amount is zero)
            ->filter();
    }
}
