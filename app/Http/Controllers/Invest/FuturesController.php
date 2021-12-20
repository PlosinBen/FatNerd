<?php

namespace App\Http\Controllers\Invest;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvestStatementFuturesResource;
use App\Service\InvestService;
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
}
