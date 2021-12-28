<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvestHistoryResource;
use App\Http\Resources\InvestFuturesResource;
use App\Service\InvestService;
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
}
