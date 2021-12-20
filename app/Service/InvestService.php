<?php

namespace App\Service;

use App\Repository\InvestHistoryRepository;
use App\Repository\InvestStatementFuturesRepository;

class InvestService
{
    public function getList()
    {
        return app(InvestHistoryRepository::class)
            ->fetchPagination([]);
    }

    public function getFuturesList()
    {
        return app(InvestStatementFuturesRepository::class)
            ->fetchPagination([]);
    }
}
