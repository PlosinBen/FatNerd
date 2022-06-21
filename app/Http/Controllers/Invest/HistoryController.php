<?php

namespace App\Http\Controllers\Invest;

use App\Data\InvestHistoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveInvestRequest;
use App\Http\Resources\InvestAccountResource;
use App\Http\Resources\InvestBalanceResource;
use App\Http\Resources\InvestHistoryResource;
use App\Models\Invest\InvestHistory;
use App\Repository\Invest\InvestAccountRepository;
use App\Service\InvestService;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index(InvestService $investService)
    {
        $investAccountId = optional(auth()->user()->InvestAccount)->id;

        if ($investAccountId === null) {
            return $this->showError('尚未啟用投資帳號，請聯絡管理員');
        }

        if (request()->has('account') && auth()->user()->isAdmin()) {
            $investAccountId = request()->get('account');
        };


        return $this
            ->title('歷史權益')
            ->view('Invest/History/Index', [
                'balances' => fn() => InvestBalanceResource::collection(
                    $investService->getList([
                        'invest_account_id' => $investAccountId
                    ])
                ),
            ]);


        $year = (int)request()->get('year');

        $years = $investService->getYears($investAccountId);

        if (!$years->contains($year)) {
            $year = $years->first();
        }

        return $this
            ->title('歷史權益')
            ->view('Invest/History/Index', [
                'year' => $year,
                'investYears' => $years,
                'investBalances' => InvestBalanceResource::collection($investService->getListByYear($investAccountId, $year)),
                'investRecords' => InvestHistoryResource::collection($investService->getRecordsByYear($investAccountId, $year))
            ]);
    }

    public function create()
    {
        return $this->view('Invest/History/Edit', [
            'investAccounts' => InvestAccountResource::collection(
                app(InvestAccountRepository::class)->fetch()
            ),
            'action' => [
                'method' => 'post',
                'url' => route('invest.history.store')
            ]
        ]);
    }

    public function store(SaveInvestRequest $saveInvestRequest, InvestService $investService)
    {
        $investService->create(
            $saveInvestRequest->investAccountId,
            Carbon::parse($saveInvestRequest->occurredAt),
            app(InvestHistoryType::class, [
                'value' => $saveInvestRequest->type
            ]),
            $saveInvestRequest->amount,
            $saveInvestRequest->note
        );

        return redirect()->route('invest.history.index');
    }

    public function destroy(InvestHistory $investHistory)
    {
        $investHistory->delete();

        return redirect()->back();
    }
}
