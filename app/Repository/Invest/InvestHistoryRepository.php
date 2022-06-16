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


    public function insert(int $investAccountId, Carbon $period, InvestHistoryType $investHistoryType, string $amount, string $note)
    {
        switch ($investHistoryType->get()) {
            case 'profit':
                $entity = $this->insertProfit(
                    $investAccountId,
                    $period,
                    $amount,
                    $note
                );
                break;
            case 'expense':
                $entity = $this->insertExpense(
                    $investAccountId,
                    $period,
                    $amount,
                    $note
                );
                break;
            case 'transfer':
                $entity = $this->insertTransfer(
                    $investAccountId,
                    $period,
                    $amount,
                    $note
                );
                break;
            case 'withdraw':
                $amount = BcMath::mul($amount, -1);
            default:
                $entity = $this->insertModel([
                    'invest_account_id' => $investAccountId,
                    'occurred_at' => $period,
                    'serial_number' => 999,
                    'type' => $investHistoryType->get(),
                    'amount' => $amount,
                    'note' => $note
                ]);
                break;
        }

        return $entity;
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
            'serial_number' => 0,
            'type' => 'profit',
            'amount' => $amount,
            'note' => $note
        ]);

        return $entity;
    }

    public function insertExpense(int $investAccountId, Carbon $period, int $expense, string $note)
    {
        $period = $period->copy()->lastOfMonth();

        // user 1 新增費用收入, 利用note做唯一性
        InvestHistory::updateOrCreate([
            'occurred_at' => $period,
            'invest_account_id' => 1,
            'type' => 'expense',
            'note' => "From {$investAccountId} Expense"
        ], [
            'amount' => $expense,
            'serial_number' => 999
        ]);

        // user $investAccountId 新增費用(負數)
        return InvestHistory::updateOrCreate([
            'occurred_at' => $period,
            'invest_account_id' => $investAccountId,
            'type' => 'expense',
        ], [
            'amount' => BcMath::mul($expense, -1),
            'note' => $note,
            'serial_number' => 999
        ]);
    }

    public function insertTransfer(int $investAccountId, Carbon $period, int $amount, string $note)
    {
        // user 1 新增出金轉存(正數)
        $this->insertModel([
            'invest_account_id' => 1,
            'occurred_at' => $period,
            'serial_number' => 999,
            'type' => 'transfer',
            'amount' => $amount,
            'note' => "From {$investAccountId} Transfer"
        ]);

        // user 新增出金
        return $this->insertModel([
            'invest_account_id' => $investAccountId,
            'occurred_at' => $period,
            'serial_number' => 999,
            'type' => 'withdraw',
            'amount' => BcMath::mul($amount, -1),
            'note' => $note
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
