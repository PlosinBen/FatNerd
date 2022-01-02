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

    public function index(InvestService $investService)
    {
        $investAccountId = auth()->id();

        $year = (int)request()->get('year');

        $years = $investService->getYears($investAccountId);

        if (!$years->contains($year)) {
            $year = $years->first();
        }

        return $this
            ->title('歷史權益')
            ->view('Invest/Index', [
                'year' => $year,
                'investYears' => $years,
                'investRecords' => $investService->getListByYear($investAccountId, $year)
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
