<?php

namespace App\Service;

use App\Repository\Invest\InvestHistoryRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InvestService
{
    protected InvestHistoryRepository $investHistoryRepository;

    public function __construct(InvestHistoryRepository $investHistoryRepository)
    {
        $this->investHistoryRepository = $investHistoryRepository;
    }

    public function getListByYear(int $investAccountId, int $year)
    {
        return $this->investHistoryRepository
            ->fetchByAccount($investAccountId, $year);
    }

    public function getYears(int $investAccountId): Collection
    {
        return $this->investHistoryRepository
            ->fetchOccurredYears($investAccountId)
            ->sortDesc()
            ->values()
            ;
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
