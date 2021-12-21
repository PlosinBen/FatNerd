<?php

namespace App\Repository\Invest;

use App\Contract\Repository;
use App\Models\Invest\InvestHistory;

class InvestHistoryRepository extends Repository
{
    protected $model = InvestHistory::class;
}
