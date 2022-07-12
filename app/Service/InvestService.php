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

class InvestService
{
    protected InvestHistoryRepository $investHistoryRepository;

    public function __construct(InvestHistoryRepository $investHistoryRepository)
    {
        $this->investHistoryRepository = $investHistoryRepository;
    }

    public function getList($filter = [], $perPage = 10)
    {
        return app(InvestBalanceRepository::class)
            ->with('InvestHistory')
            ->perPage($perPage)
            ->fetchPagination(
                array_merge([
                    'orderBy' => 'period DESC'
                ], $filter)
            );
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
            ->fetchSumOfTypeByPeriod($period);
    }

    public function create(int $investAccountId, Carbon $occurredAt, InvestHistoryType $investHistoryType, string $amount, ?string $note = null)
    {
        $this->investHistoryRepository->insert(
            $investAccountId,
            $occurredAt,
            $investHistoryType,
            $amount,
            $note ?? ''
        );

        $this->updateHistorySerialNumber($investAccountId, $occurredAt);

        $this->updateBalance($investAccountId, $occurredAt);
    }

    protected function updateHistorySerialNumber(int $investAccountId, Carbon $period)
    {
        $this->investHistoryRepository
            ->fetchByAccountPeriod($investAccountId, $period)
            ->sortBy(function (InvestHistory $investHistory) {
                $prefix = InvestHistoryType::getForceSortNumber($investHistory->type);
                $occurredDay = $investHistory->occurred_at->format('d');
                $createdDay = $investHistory->created_at->format('d');

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
            ->fetchByAccountPeriod($investAccountId, $period->copy()->firstOfMonth()->subMonth());

        $investTypeAmount = $this->investHistoryRepository
            ->fetchByAccountPeriod($investAccountId, $period->copy())
            ->groupBy('type')
            ->map(function ($investHistoryTypeGroup) {
                return $investHistoryTypeGroup->reduce(
                    fn($current, InvestHistory $investHistory) => BcMath::add($current, $investHistory->amount),
                    '0'
                );
            });

//        if( $investAccountId === 4 && $period->isSameMonth('2019-12', true) && $investTypeAmount->has('expense') ) {
//            dd(
//                $period,
//                $period->copy()->subMonth(),
//                $prePeriodInvestBalance,
//                $investTypeAmount
//            );
//        }

        # Calc Balance
        $investTypeAmount->put(
            'balance',
            BcMath::add(
                optional($prePeriodInvestBalance)->balance ?? 0,
                ...$investTypeAmount->values()
            )
        );

        if ($investTypeAmount->filter(fn($column) => !BcMath::equal($column, 0))->count() === 0) {
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

    public function getComputeExceptionBalance(Carbon $start, Carbon $end, bool $updateBeforeFetch = false)
    {
        /**
         * @var InvestBalanceRepository $investBalanceRepository
         */
        $investBalanceRepository = app(InvestBalanceRepository::class);

        return $investBalanceRepository
            ->fetchAccountSumOfTypeByPeriod($start, $end)
            ->filter(fn($entity) => $entity->profit > 0)
            ->filter(fn($entity) => $entity->invest_account_id > 1)
            ->filter(fn($entity) => $entity->InvestAccount->contract !== null)
            ->map(function ($entity) use ($investBalanceRepository, $end) {
                $lastBalance = $investBalanceRepository
                    ->fetchByAccountPeriod(
                        $entity->invest_account_id,
                        $end
                    )->balance;

                $entity->balance = $lastBalance;

                return $entity;
            });
    }
}
