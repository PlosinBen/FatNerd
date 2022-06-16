<?php

namespace Database\Seeders;

use App\Data\InvestHistoryType;
use App\Models\Invest\InvestAccount;
use App\Models\User;
use App\Service\FuturesService;
use App\Service\InvestService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\InvestAccount::factory(10)->create();
        $this
            ->initUser()
            ->initInvestAccount();

        for ($period = Carbon::parse('2018-08'); $period->lessThan(Carbon::now()); $period->lastOfMonth()->addDay()) {
//            echo "Run ".$period->toDateString() . PHP_EOL;
            $this->initTransfer($period)
                ->initDepositAndWithdraw($period)
                ->initFutures($period);
//            echo PHP_EOL;
        }
    }

    protected function initUser()
    {
        User::create([
            'name' => 'Ben',
            'avatar' => ''
        ]);

        return $this;
    }

    protected function initInvestAccount()
    {
        collect([
            [
                'alias' => 'B',
                'user_id' => 1,
                'start_year_at' => 2018,
            ],
            [
                'alias' => 'A',
                'start_year_at' => 2018,
            ],
            [
                'alias' => 'Wu',
                'contract' => 'specially',
                'start_year_at' => 2019,
            ],
            [
                'alias' => '熊',
                'contract' => 'friend',
                'start_year_at' => 2019,
            ],
            [
                'alias' => '金',
                'start_year_at' => 2018,
            ],
            [
                'alias' => 'Emma',
                'contract' => 'friend',
                'start_year_at' => 2020,
            ],
            [
                'alias' => 'Allie',
                'contract' => 'specially',
                'start_year_at' => 2020,
            ]
        ])
            ->each(fn($data) => InvestAccount::create($data));

        return $this;
    }

    protected function initDepositAndWithdraw(Carbon $period)
    {
        $investService = app()->make(InvestService::class);

        collect([
            [1, '2018-09-01', 'deposit', 148956],
            [1, '2018-12-29', 'deposit', 10000],
            [1, '2019-01-15', 'deposit', 3000],
            [1, '2019-02-11', 'deposit', 50000],
            [1, '2019-03-27', 'deposit', 3000],
            [1, '2019-05-14', 'deposit', 3000],
            [1, '2019-06-11', 'deposit', 3000],
            [1, '2019-07-09', 'deposit', 3000],
            [1, '2019-08-08', 'deposit', 3000],
            [1, '2019-09-05', 'deposit', 3000],
            [1, '2019-10-09', 'deposit', 3000],
            [1, '2019-11-11', 'deposit', 3000],
            [1, '2019-12-23	', 'deposit', 20000],
            [1, '2020-01-31', 'deposit', 600000],
            [1, '2020-02-19', 'deposit', 3000],
            [1, '2020-03-13', 'deposit', 3000],
            [1, '2020-04-08', 'deposit', 3000],
            [1, '2020-05-05', 'deposit', 5000],
            [1, '2020-06-11', 'deposit', 3000],
            [1, '2020-07-01', 'deposit', 3000],
            [1, '2020-07-13', 'deposit', 5000],
            [1, '2020-08-05', 'deposit', 5000],
            [1, '2020-08-12', 'deposit', 5000],
            [1, '2020-08-27', 'withdraw', 350000],
            [1, '2020-09-29', 'deposit', 200000],
            [1, '2020-10-28', 'deposit', 20000],
            [1, '2020-11-27', 'deposit', 5000],
            [1, '2020-12-29', 'deposit', 2000],

            [1, '2021-01-20', 'deposit', 2000],
            [1, '2021-02-21', 'deposit', 2000],
            [1, '2021-03-16', 'deposit', 2000],
            [1, '2021-04-13', 'deposit', 2000],
            [1, '2021-05-18', 'deposit', 2000],
            [1, '2021-06-20', 'deposit', 2000],
            [1, '2021-07-15', 'deposit', 2000],
            [1, '2021-08-12', 'deposit', 2000],
            [1, '2021-09-14', 'deposit', 2000],
            [1, '2021-10-19', 'deposit', 2000],
            [1, '2021-11-11', 'deposit', 2000],
            [1, '2021-12-15', 'deposit', 2000],
            [1, '2022-01-15', 'deposit', 2000],
            [1, '2022-02-18', 'deposit', 2000],
            [1, '2022-03-15', 'deposit', 2000],
            [1, '2022-04-15', 'deposit', 5000],
            [1, '2022-05-16', 'deposit', 5000],

            [2, '2018-10-20', 'deposit', 100000],
            [2, '2020-04-14', 'deposit', 100000],
            [2, '2021-08-24', 'deposit', 50000],
            [2, '2021-09-10', 'deposit', 50000],

            [3, '2019-11-13', 'deposit', 2000],
            [3, '2019-12-23', 'deposit', 2000],
            [3, '2020-01-31', 'deposit', 2000],
            [3, '2020-02-19', 'deposit', 2000],
            [3, '2020-03-13', 'deposit', 2000],
            [3, '2020-04-13', 'deposit', 2000],
            [3, '2020-05-15', 'deposit', 2000],
            [3, '2020-06-11', 'deposit', 3000],
            [3, '2020-07-13', 'deposit', 3000],
            [3, '2020-07-16', 'deposit', 1639],
            [3, '2020-08-11', 'deposit', 3000],
            [3, '2020-09-10', 'deposit', 3000],
            [3, '2020-10-10', 'deposit', 3000],
            [3, '2020-11-10', 'deposit', 3000],
            [3, '2020-12-10', 'deposit', 3000],
            [3, '2021-01-10', 'deposit', 3000],
            [3, '2021-02-21', 'deposit', 5000],
            [3, '2021-03-11', 'deposit', 5000],
            [3, '2021-04-10', 'deposit', 7000],
            [3, '2021-05-10', 'deposit', 5000],
            [3, '2021-06-10', 'deposit', 5000],

            [4, '2019-11-21', 'deposit', 10000],
            [4, '2020-06-30', 'deposit', 13605],

            [5, '2020-03-20', 'deposit', 5000],
            [5, '2020-05-27', 'deposit', 3000],

            [6, '2020-06-19', 'deposit', 20000],

            [7, '2020-12-30', 'deposit', 27216]
        ])
            ->filter(fn($data) => $period->isSameMonth($data[1], true))
            ->each(fn($data) => $investService->create(
                $data[0],
                Carbon::parse($data[1]),
                new InvestHistoryType($data[2]),
                $data[3]
            ));

        return $this;
    }

    protected function initTransfer(Carbon $period)
    {
        $investService = app()->make(InvestService::class);

        collect([
            [5, '2020-07-29', 8629],
            [3, '2021-01-17', 31459],
            [3, '2021-08-16', 20000],
        ])
            ->filter(fn($data) => $period->isSameMonth($data[1], true))
            ->each(fn($data) => $investService->create(
                $data[0],
                Carbon::parse($data[1]),
                InvestHistoryType::transfer(),
                $data[2]
            ));

        return $this;
    }

    protected function initFutures(Carbon $period)
    {
        $futuresService = app()->make(FuturesService::class);

        collect([
            //[日期, 結餘, 未平倉, 沖銷, 入金, 出金]
            ['2018-09', 161506, 12550, null],
            ['2018-10', 241029, 11450, null],
            ['2018-11', 239339, 12000, null],
            ['2018-12', 276552, 7800, null],
            ['2019-01', 333144, 0, null],
            ['2019-02', 403849, 5000, null],
            ['2019-03', 400437, 3300, null],
            ['2019-04', 412727, -9150, null],
            ['2019-05', 533288, 19580, null],
            ['2019-06', 498742, -8850, null],
            ['2019-07', 267297, -9520, null],
            ['2019-08', 346254, 16910, null],
            ['2019-09', 409290, 4900, null],
            ['2019-10', 448812, 26820, null],
            ['2019-11', 408469, 7920, null],
            ['2019-12', 565513, 1780, null],
            ['2020-01', 1110184, 74510, -131920],
            ['2020-02', 1249247, 147750, 71090],
            ['2020-03', 1481732, 0, 377000],
            ['2020-04', 1891941, 40800, 198790],
            ['2020-05', 1800657, 6250, -90527],
            ['2020-06', 1901887, -4300, 96004],
            ['2020-07', 2081876, -10850, 184509, null, 0],
            ['2020-08', 1647065, -51350, -3002],
            ['2020-09', 1689311, 200, -212750],
            ['2020-10', 1665002, 117550, -111173],
            ['2020-11', 2069267, -19550, 540150],
            ['2020-12', 2027190, 41800, -103427],
            ['2021-01', 2071980, 96650, -9794, null, 0],
            ['2021-02', 2041993, 63350, -3760],
            ['2021-03', 1593499, 64200, -473751],
            ['2021-04', 1658541, 0, 148342],
            ['2021-05', 2304896, 43650, 609393],
            ['2021-06', 2243681, 28550, -46319],
            ['2021-07', 2188571, -8000, -17638],
            ['2021-08', 2798167, 206700, 344217, null, 0],
            ['2021-09', 2419291, 750, -223094],
            ['2021-10', 2297759, -36800, -83847],
            ['2021-11', 2591873, -7550, 264452],
            ['2021-12', 2624673, 108000, -82501],
            ['2022-01', 2704690, 80150, 107824],
            ['2022-02', 2610063, 80500, -88812],
            ['2022-03', 2657171, -19900, 141439],
            ['2022-04', 2792552, 550, 114650],
            ['2022-05', 2913539, 208150, -86272],
        ])
            ->filter(fn($data) => $period->isSameMonth($data[0], true))
            ->each(fn($data) => $futuresService
                ->distributeProfit(
                    $futuresService->createFutures(
                        Carbon::parse(array_shift($data)),
                        ...$data
                    )
                )
                ->distributeExpense($period)
            );
    }
}
