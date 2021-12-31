<?php

namespace App\Service;

use App\Repository\Invest\InvestHistoryRepository;
use Carbon\Carbon;

class InvestService
{
    protected InvestHistoryRepository $investHistoryRepository;

    public function __construct(InvestHistoryRepository $investHistoryRepository)
    {
        $this->investHistoryRepository = $investHistoryRepository;
    }

    public function getList(int $investAccountId)
    {
        return $this->investHistoryRepository
            ->fetchByAccount($investAccountId);
    }

    public function create(int $investAccountId, Carbon $occurredAt, string $type, int $amount, ?string $note)
    {
        $this->investHistoryRepository->insert(
            $occurredAt,
            $investAccountId,
            $type,
            $amount,
            $note ?? ''
        );

        $this->investHistoryRepository->updateBalance($investAccountId, $occurredAt);
    }
}
