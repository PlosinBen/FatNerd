<?php

namespace Database\Seeders;

use App\Models\Invest\InvestAccount;
use App\Models\Invest\InvestBalance;
use App\Models\Invest\InvestHistory;
use App\Models\Invest\InvestFutures;
use App\Models\User;
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
        $this->initUser();
        $this->initInvest();
    }

    protected function initUser()
    {
        User::create([
            'name' => 'Ben',
            'avatar' => ''
        ]);
    }

    protected function initInvest()
    {
        InvestAccount::create([
            'alias' => 'B',
            'user_id' => 1,
            'contract' => null,
            'start_year_at' => 2018,
        ])
            ->InvestHistories()->create([
                'serial_number' => 1,
                'occurred_at' => '2018-09-01',
                'type' => 'deposit',
                'amount' => 148956,
//                'balance' => 148956,
            ]);
        InvestBalance::create([
            'period' => '2018-09',
            'invest_account_id' => 1,
            'balance' => 148956
        ]);

        $A = InvestAccount::create([
            'alias' => 'A',
            'user_id' => null,
            'contract' => null,
            'start_year_at' => 2018,
        ])
            ->InvestHistories()->create([
                'serial_number' => 1,
                'occurred_at' => '2018-10-20',
                'type' => 'deposit',
                'amount' => 100000,
//                'balance' => 100000,
            ]);
        InvestBalance::create([
            'period' => '2018-10',
            'invest_account_id' => 2,
            'deposit' => 100000,
            'balance' => 100000
        ]);

        InvestFutures::create([
            'period' => '2018-09',
            'commitment' => 161506,
            'open_interest' => 12550,
            'real_commitment' => 148956
        ]);
    }
}
