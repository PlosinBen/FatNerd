<?php

namespace App\Http\Controllers\Invest;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveFuturesRequest;
use App\Http\Resources\InvestBalanceResource;
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
            'InvestBalances' => InvestBalanceResource::collection(
                $investFutures->InvestBalance->load('InvestAccount')
            )
        ]);
    }

    public function create(InvestService $investService)
    {


        return $this
            ->view('Futures/Edit', [
                'amountOfType' => [],
                'action' => [
                    'method' => 'post',
                    'url' => route('invest.futures.store')
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
                    'url' => route('invest.futures.update', $investFutures->period)
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

        $period = Carbon::parse($requestData->get('period'));

        $futures = $futuresService->createFutures(
            $period,
            $requestData->get('commitment'),
            $requestData->get('open_interest'),
            $requestData->get('cover_profit'),
            $requestData->get('deposit', 0),
            $requestData->get('withdraw', 0),
        );

        $this->distributeProfit($period);

        return redirect()->route('invest.futures.index');
    }

    protected function distributeProfit(Carbon $period)
    {
        /**
         * @var FuturesService $futuresService
         */
        $futuresService = app(FuturesService::class);

        while ($futures = $futuresService->get($period)) {
            $futuresService->distributeProfit($futures);

            $period->addMonth();
        }
    }
}
