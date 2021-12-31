<?php

namespace App\Http\Controllers\Invest;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveFuturesRequest;
use App\Http\Resources\InvestFuturesProfitResource;
use App\Http\Resources\InvestFuturesResource;
use App\Models\Invest\InvestFutures;
use App\Service\FuturesService;
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
                    app(FuturesService::class)->getList()
                )
            )
            ->view('Futures/Index', [
            ]);
    }

    public function show(InvestFutures $investFutures)
    {
        return $this->view('Futures/Show', [
            'investFutures' => InvestFuturesResource::make($investFutures),
            'investFuturesProfits' => InvestFuturesProfitResource::collection(
                $investFutures->InvestFuturesProfits->load('InvestAccount')
            )
        ]);
    }

    public function create()
    {
        return $this
            ->view('Futures/Edit', [
                'action' => [
                    'method' => 'post',
                    'url' => route('futures.store')
                ]
            ]);
    }

    public function edit(InvestFutures $investFutures)
    {
        return $this
            ->view('Futures/Edit', [
                'investFutures' => InvestFuturesResource::make($investFutures),
                'action' => [
                    'method' => 'put',
                    'url' => route('futures.update', $investFutures->period)
                ]
            ]);
    }

    public function update(InvestFutures $investFutures, SaveFuturesRequest $saveFuturesRequest, FuturesService $investService)
    {
        return $this->store($saveFuturesRequest, $investService);
    }

    public function store(SaveFuturesRequest $saveFuturesRequest, FuturesService $futuresService)
    {
        $requestData = $saveFuturesRequest->safe()->collect();

        $futures = $futuresService->createFutures(
            Carbon::parse($requestData->get('period')),
            $requestData->get('commitment'),
            $requestData->get('open_interest'),
            $requestData->get('cover_profit')
        );

        $futuresService->distributeProfit($futures);

        return redirect()->route('futures.index');
    }
}
