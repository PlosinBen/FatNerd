<?php

namespace App\Http\Controllers\Invest;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveFuturesRequest;
use App\Http\Resources\InvestStatementFuturesResource;
use App\Service\InvestService;
use App\Service\StatementService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FuturesController extends Controller
{
    protected $title = '對帳單';

    public function index()
    {
        return $this
            ->paginationList(
                InvestStatementFuturesResource::collection(
                    app(InvestService::class)->getFuturesList()
                )
            )
            ->view('Invest/Futures', [
            ]);
    }

    public function create()
    {
        return $this
            ->view('Invest/Futures/Show', [
                'action' => [
                    'method' => 'post',
                    'url' => route('futures.store')
                ]
            ]);
    }

    public function store(SaveFuturesRequest $saveFuturesRequest, InvestService $investService)
    {
        $requestData = $saveFuturesRequest->safe()->collect();

        $futures = $investService->createFutures(
            Carbon::parse($requestData->get('period')),
            $requestData->get('commitment'),
            $requestData->get('open_interest'),
            $requestData->get('profit')
        );

        dd(
            $futures
        );
    }
}
