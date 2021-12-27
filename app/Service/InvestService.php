<?php

namespace App\Service;

use App\Models\Invest\InvestFutures;
use App\Repository\Invest\InvestFuturesProfitRepository;
use App\Repository\Invest\InvestHistoryRepository;
use App\Repository\Invest\InvestFuturesRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InvestService
{
    protected InvestHistoryRepository $investHistoryRepository;

    public function __construct(InvestHistoryRepository $investHistoryRepository)
    {
        $this->investHistoryRepository = $investHistoryRepository;
    }

    public function getList($filter = [])
    {
        return $this->investHistoryRepository
            ->fetchPagination(['orderBy' => 'period Desc'] + $filter);
    }
}
