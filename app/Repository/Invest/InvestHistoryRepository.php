<?php

namespace App\Repository\Invest;

use App\Contract\Repository;
use App\Data\InvestHistoryType;
use App\Models\Invest\InvestAccount;
use App\Models\Invest\InvestHistory;
use App\Support\BcMath;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class InvestHistoryRepository extends Repository
{
    protected $model = InvestHistory::class;

    /**
     * this use for IDE method hint
     * @return InvestHistory|Model
     */
    protected function getModelInstance()
    {
        return parent::getModelInstance();
    }

    /**
     * @param int $investAccountId
     * @param Carbon $period
     * @return Collection
     */
    public function fetchByAccountPeriod(int $investAccountId, Carbon $period)
    {
        return $this->fetch([
            'invest_account_id' => $investAccountId,
            'period' => $period->copy()
        ]);
    }

    /**
     * @param int $investAccountId
     * @param Carbon $period
     * @return string
     */
    public function fetchPrePeriodBalance(int $investAccountId, Carbon $period)
    {
        $entity = $this->modelSetFilter([
            'invest_account_id' => $investAccountId,
            'period' => $period->copy()->subMonth(),
            'orderBy' => 'serial_number,DESC'
        ])->first();

        return optional($entity)->balance ?? '0';
    }

    public function fetchByAccount(int $investAccountId, int $year)
    {
        return $this->fetch([
            'orderBy' => 'occurred_at DESC,id DESC',
            'invest_account_id' => $investAccountId,
            'year' => $year
        ]);
    }

    public function fetchByPeriod(Carbon $period): Collection
    {
        return $this->fetch([
            'period' => $period
        ]);
    }

    public function fetchAccountsComputable(Carbon $period)
    {
        $prePeriod = $period->copy()->subMonth();

        return $this->getModelInstance()
            ->period($prePeriod)
            ->orderBy('occurred_at', 'desc')
            ->orderByRaw("FIELD(type, 'expense', 'profit')")
            ->get()
            ->groupBy('invest_account_id')
            ->map(fn($investAccountRecords) => $investAccountRecords->first()->balance)
            ->dd();
    }

    public function calcNetDepositWithdraw(Carbon $period)
    {
        return $this->getModelInstance()
            ->period($period)
            ->whereIn('type', ['deposit', 'withdraw', 'transfer'])
            ->sum('amount');
    }

    public function insert(int $investAccountId, Carbon $period, InvestHistoryType $investHistoryType, string $amount, string $note)
    {
        return $this->insertModel([
            'invest_account_id' => $investAccountId,
            'occurred_at' => $period,
            'serial_number' => 999,
            'type' => $investHistoryType->get(),
            'amount' => $amount,
            'balance' => 0,
            'note' => $note
        ]);


//        $periodRecords->push(
//            $newEntity = $this->setEntityColumns($this->getModelInstance(), [
//                'invest_account_id' => $investAccountId,
//                'occurred_at' => $period,
//                'type' => $investHistoryType->get(),
//                'amount' => $amount,
//                'balance' => 0,
//                'note' => $note
//            ])
//        )
//            ->sortBy(function (InvestHistory $investHistory) {
//                $prefix = InvestHistoryType::getForceSortNumber($investHistory->type);
//                $occurredDay = $investHistory->occurred_at->format('d');
//                $createdDay = optional($investHistory->created_at)->format('d') ?? '99';
//
//                return "{$prefix}{$occurredDay}{$createdDay}";
//            })
//            ->dd()
//            ->each(fn(InvestHistory $investHistory, $index) => $this->updateModel($investHistory, ['serial_number' => $index]));
//
//        return $newEntity;
    }

    /**
     * @param int|InvestHistory $entity
     * @param string $balance
     * @param int|null $serialNumber
     * @return InvestHistory|Model|null
     */
    public function updateBalance($entity, string $balance, int $serialNumber = null)
    {
        return $this->updateModel($entity, array_filter([
            'balance' => $balance,
            'serial_number' => $serialNumber
        ]));
    }

//    public function updateBalance(int $investAccountId, Carbon $period)
//    {
//        /**
//         * @var Collection $periodRecords
//         */
//        $periodRecords = $this->getModelInstance()
//            ->investAccountId($investAccountId)
//            ->period($period->copy())
//            ->orderBy('occurred_at', 'ASC')
//            ->orderBy('created_at', 'ASC')
//            ->get();
//
//        $balance = $this->fetchLastBalanceOfPeriod($investAccountId, $period->copy()->subMonth());
//
//        $this->getModelInstance()
//            ->investAccountId($investAccountId)
//            ->period($period)
//            ->orderBy('occurred_at', 'ASC')
//            ->orderBy('id', 'ASC')
//            ->lazy()
//            ->reduce(function ($preBalance, InvestHistory $investHistory) {
//                $investHistory = $this->updateModel($investHistory, [
//                    'balance' => BcMath::add($preBalance, $investHistory->amount)
//                ]);
//
//                return $investHistory->balance;
//            }, $balance);
//    }

    /**
     * @param int $investAccountId
     * @param Carbon $period
     * @return string
     */
    public function fetchLastBalanceOfPeriod(int $investAccountId, Carbon $period): string
    {
        $entity = $this->getModelInstance()
            ->investAccountId($investAccountId)
            ->period($period)
            ->orderBy('serial_number', 'ASC')
            ->select(['balance'])
            ->first();

        return optional($entity)->balance ?? '0';
    }

    public function insertProfit(int $investAccountId, Carbon $period, int $profit)
    {
        return InvestHistory::updateOrCreate([
            'occurred_at' => $period->copy()->lastOfMonth(),
            'invest_account_id' => $investAccountId,
            'type' => 'profit',
        ], [
            'amount' => $profit,
            'balance' => 0
        ]);
    }

    public function insertExpenseIncome(int $investAccountId, Carbon $period, int $expense)
    {

    }

    public function insertExpense(int $investAccountId, Carbon $period, int $expense)
    {
        return InvestHistory::updateOrCreate([
            'occurred_at' => $period->copy()->lastOfMonth,
            'invest_account_id' => $investAccountId,
            'type' => 'expense',
        ], [
            'amount' => $expense,
            'balance' => 0
        ]);
    }

    protected function assignRecordId(InvestHistory $investHistory)
    {
        //取得在 $investHistory 以前的最後一筆

        //FIELD(type, 'profit', 'expense')

        $beforeLastRecord = $this->getModelInstance()
            ->where('invest_account_id', $investHistory->invest_account_id)
            ->where('occurred_at', '<=', $investHistory->occurred_at)
            ->whereNotIn('type', [
                'profit', 'expense'
            ])
            ->orderBy('invest_account_record_id', 'DESC')
            ->first();
    }

    public function fetchAccountComputableAndQuota(InvestAccount $investAccount, Carbon $period)
    {
        $entity = InvestHistory::period($period)
            ->investAccountId($investAccount->id)
            ->where('computable', '>', 0)
            ->first();

        if ($entity) {
            return $entity->only('computable', 'quota');
        }

        $preHistory = InvestHistory::period($period->copy()->subMonth())
            ->investAccountId($investAccount->id)
            ->where('balance', '>', 0)
            ->first();

        if ($preHistory === null) {
            return null;
        }

        return InvestHistory::firstOrCreate([
            'period' => $period,
            'invest_account_id' => $investAccount->id,

            'computable' => $preHistory->balance,
            'quota' => floor($preHistory->balance / 5000),

            'balance' => $preHistory->balance,
        ])->only('computable', 'quota');
    }

    public function fetchOccurredYears(int $investAccountId)
    {
        return $this->getModelInstance()
            ->investAccountId($investAccountId)
            ->groupByRaw('year(occurred_at)')
            ->selectRaw('year(occurred_at) as year')
            ->get()
            ->pluck('year');
    }
}
