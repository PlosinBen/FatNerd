<?php

namespace App\Http\Controllers\Invest;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveFuturesRequest;
use App\Http\Resources\InvestFuturesProfitResource;
use App\Http\Resources\InvestFuturesResource;
use App\Models\Invest\InvestFutures;
use App\Service\InvestService;
use Carbon\Carbon;

class FuturesController extends Controller
{
    protected $title = '對帳單';

    public function index()
    {
        return $this
            ->paginationList(
                InvestFuturesResource::collection(
                    app(InvestService::class)->getFuturesList()
                )
            )
            ->view('Invest/Futures/Index', [
            ]);
    }

    public function show(InvestFutures $investFutures)
    {
        return $this->view('Invest/Futures/Show', [
            'investFutures' => InvestFuturesResource::make($investFutures),
            'investFuturesProfits' => InvestFuturesProfitResource::collection(
                $investFutures->InvestFuturesProfits->load('InvestAccount')
            )
        ]);
    }

    public function create()
    {
        return $this
            ->view('Invest/Futures/Edit', [
                'action' => [
                    'method' => 'post',
                    'url' => route('futures.store')
                ]
            ]);
    }

    public function edit(InvestFutures $investFutures)
    {
        return $this
            ->view('Invest/Futures/Edit', [
                'investFutures' => InvestFuturesResource::make($investFutures),
                'action' => [
                    'method' => 'put',
                    'url' => route('futures.update', $investFutures->period)
                ]
            ]);
    }

    public function update(InvestFutures $investFutures, SaveFuturesRequest $saveFuturesRequest, InvestService $investService)
    {
        return $this->store($saveFuturesRequest, $investService);
    }

    public function store(SaveFuturesRequest $saveFuturesRequest, InvestService $investService)
    {
        $requestData = $saveFuturesRequest->safe()->collect();

        $futures = $investService->createFutures(
            Carbon::parse($requestData->get('period')),
            $requestData->get('commitment'),
            $requestData->get('open_interest'),
            $requestData->get('cover_profit')
        );

        $investService->distributeProfit($futures);

        return redirect()->route('futures.index');
    }
}
