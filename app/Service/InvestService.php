<?php

namespace App\Service;

use App\Repository\InvestHistoryRepository;

class InvestService
{
    public function getList()
    {
        return app(InvestHistoryRepository::class)
            ->fetchPagination([]);
    }
}
