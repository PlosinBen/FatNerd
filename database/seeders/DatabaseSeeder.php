<?php

namespace Database\Seeders;

use App\Models\Invest\InvestAccount;
use App\Models\Invest\InvestHistory;
use App\Models\Invest\InvestStatementFutures;
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
        ]);

        InvestHistory::create([
            'period' => '2018-09-01',
            'invest_user_id' => 1,
            'balance' => 148956
        ]);

        InvestStatementFutures::create([
            'period' => '2018-09-01',
            'commitment' => 161506,
            'open_interest' => 12550,
            'real_commitment' => 148956
        ]);

        InvestStatementFutures::create([
            'period' => '2018-10-01',
            'commitment' => 241029,
            'open_interest' => 11450,
            'real_commitment' => 0
        ]);
    }
}
