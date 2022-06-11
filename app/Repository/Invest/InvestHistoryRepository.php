<?php

namespace App\Repository\Invest;

use App\Contract\Repository;
use App\Data\InvestHistoryType;
use App\Models\Invest\InvestAccount;
use App\Models\Invest\InvestHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

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

    public function fetchByAccountYear(int $investAccountId, int $year)
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
//            'balance' => 0,
            'note' => $note
        ]);
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

    /**
     * @param int|InvestHistory $entity
     * @param int|null $serialNumber
     * @return InvestHistory|Model|null
     */
    public function updateSerialNumber($entity, int $serialNumber)
    {
        return $this->updateModel($entity, [
            'serial_number' => $serialNumber
        ]);
    }

    public function insertProfit(int $investAccountId, Carbon $period, string $amount, string $note)
    {
        $lastOfMonth = $period->copy()->lastOfMonth();

        $entity = $this->fetch([
            'invest_account_id' => $investAccountId,
            'type' => 'profit',
            'period' => $lastOfMonth
        ])->first() ?? $this->getModelInstance();

        $this->updateModel($entity, [
            'occurred_at' => $lastOfMonth,
            'invest_account_id' => $investAccountId,
            'type' => 'profit',
            'amount' => $amount,
            'note' => $note
        ]);

        return $entity;
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
