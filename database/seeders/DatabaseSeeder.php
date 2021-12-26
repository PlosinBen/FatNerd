<?php

namespace Database\Seeders;

use App\Models\Invest\InvestAccount;
use App\Models\Invest\InvestHistory;
use App\Models\Invest\InvestFutures;
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

        $this->initInvest();
    }

    protected function initInvest()
    {
        InvestAccount::create([
            'alias' => 'B',
            'user_id' => 1,
            'contract' => null
        ])
            ->InvestHistories()->create([
                'occurred_at' => '2018-09-01',
                'type' => 'deposit',
                'amount' => 148956,
                'balance' => 148956,
            ]);

        $A = InvestAccount::create([
            'alias' => 'A',
            'user_id' => null,
            'contract' => null
        ])
            ->InvestHistories()->create([
                'occurred_at' => '2018-10-20',
                'type' => 'deposit',
                'amount' => 100000,
                'balance' => 100000,
            ]);


        InvestFutures::create([
            'period' => '2018-09',
            'commitment' => 161506,
            'open_interest' => 12550,
            'real_commitment' => 148956
        ]);
    }
}
