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

    }

    public function history()
    {
        return $this
            ->paginationList(
                InvestHistoryResource::collection(
                    app(InvestService::class)->getList()
                )
            )
            ->view('Invest/History', [

            ]);
    }
}
