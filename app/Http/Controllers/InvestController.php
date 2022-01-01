<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveInvestRequest;
use App\Http\Resources\InvestAccountResource;
use App\Repository\Invest\InvestAccountRepository;
use App\Service\InvestService;
use Carbon\Carbon;

class InvestController extends Controller
{
    protected $title = '投資';

    public function index()
    {
        $year = 2018;

        return $this
            ->title('歷史權益')
            ->view('Invest/Index', [
                'year' => 2018,
                'investYears' => [
                    2021,
                    2020,
                    2019,
                    2018
                ],
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
