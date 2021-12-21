<?php

namespace App\Service;

use App\Repository\Invest\InvestStatementFuturesRepository;
use Carbon\Carbon;

class StatementService
{
    protected InvestStatementFuturesRepository $investStatementFuturesRepository;

    public function __construct(InvestStatementFuturesRepository $investStatementFuturesRepository)
    {
        $this->investStatementFuturesRepository = $investStatementFuturesRepository;
    }

    public function getList()
    {

    }

    public function get($period)
    {

    }

    public function create(Carbon $period, int $commitment, int $openInterest, int $profit)
    {
        $prePeriod = $period->subMonth();

        dd(
            $prePeriod
        );
    }
}
