<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveInvestRequest;
use App\Http\Resources\InvestAccountResource;
use App\Http\Resources\InvestHistoryResource;
use App\Http\Resources\InvestFuturesResource;
use App\Models\Invest\InvestHistory;
use App\Repository\Invest\InvestAccountRepository;
use App\Service\InvestService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvestController extends Controller
{
    protected $title = '投資';

    public function index()
    {
        return $this
            ->view('Invest/Index', [
                'investRecords' => app(InvestService::class)->getList(1)
                    ->mapToGroups(function ($investHistory) {
                        return [
                            $investHistory->occurred_at->format('Y-m') => $investHistory
                        ];
                    })
                    ->map(function ($monthHistories) {
                        return $monthHistories
                            ->groupBy('type')
                            ->map(fn($groupHistories) => $groupHistories->sum('amount'))
                            ->put('balance', (int)$monthHistories->first()->balance)
                            ->put('detail', $monthHistories);
                    })
            ]);
    }

    public function create()
    {
        return $this->view('Invest/Edit', [
            'investAccounts' => InvestAccountResource::collection(
                app(InvestAccountRepository::class)->fetch()
            ),
            'action' => [
                'method' => 'post',
                'url' => route('invest.store')
            ]
        ]);
    }

    public function store(SaveInvestRequest $saveInvestRequest, InvestService $investService)
    {
        $occurredAt = Carbon::parse($saveInvestRequest->occurredAt);

        $investService->create(
            $saveInvestRequest->investAccountId,
            $occurredAt,
            $saveInvestRequest->type,
            $saveInvestRequest->amount,
            $saveInvestRequest->note
        );

        return redirect()->route('invest.index');
    }
}
