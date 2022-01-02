<?php

namespace App\Repository\Invest;

use App\Contract\Repository;
use App\Models\Invest\InvestAccount;
use App\Models\Invest\InvestFuturesProfit;
use App\Models\Invest\InvestHistory;
use Carbon\Carbon;

class InvestHistoryRepository extends Repository
{
    protected $model = InvestHistory::class;

    public function fetchByAccount(int $investAccountId, int $year)
    {
        return $this->fetch([
            'orderBy' => 'occurred_at DESC,id DESC',
            'invest_account_id' => $investAccountId,
            'year' => $year
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
            ->map(fn($investAccountRecords) => $investAccountRecords->first()->balance);
    }

    public function calcNetDepositWithdraw(Carbon $period)
    {
        return $this->getModelInstance()
            ->period($period)
            ->whereIn('type', ['deposit', 'withdraw', 'transfer'])
            ->sum('amount');
    }

    public function insert(Carbon $period, int $investAccountId, string $type, int $amount, string $note)
    {
        return $this->insertModel([
            'invest_account_id' => $investAccountId,
            'occurred_at' => $period,
            'type' => $type,
            'amount' => $amount,
            'balance' => 0,
            'note' => $note
        ]);
    }

    public function fetchLastBalanceOfMonth(int $investAccountId, Carbon $period, array $exclude = [])
    {
        $entity = $this->getModelInstance()
            ->investAccountId($investAccountId)
            ->where('occurred_at', '<=', $period->copy()->lastOfMonth()->toDateString())
            ->whereNotIn('type', $exclude)
            ->orderBy('occurred_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->select(['balance'])
            ->first();

        #ExcludeProfit
        return optional($entity)->balance ?? 0;
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

    public function insertExpense(int $investAccountId, Carbon $period, int $profit)
    {
        return InvestHistory::updateOrCreate([
            'occurred_at' => $period->copy()->lastOfMonth,
            'invest_account_id' => $investAccountId,
            'type' => 'profit',
        ], [
            'amount' => $profit,
            'balance' => 0
        ]);
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

    public function updateBalance(int $investAccountId, Carbon $period)
    {
        $balance = $this->fetchLastBalanceOfMonth($investAccountId, $period->copy()->subMonth());

        $this->getModelInstance()
            ->investAccountId($investAccountId)
            ->where('occurred_at', '>=', $period->format('Y-m-01'))
            ->orderBy('occurred_at', 'ASC')
            ->orderBy('id', 'ASC')
            ->lazy()
            ->each(function (InvestHistory $investHistory) use (&$balance) {
                $investHistory->balance = $balance = $balance + $investHistory->amount;
                $investHistory->save();
            });
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
