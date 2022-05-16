<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestFuturesProfitTable extends Migration
{
    protected $table = 'invest_futures_profit';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->foreignId('invest_futures_id');
        $table->foreignId('invest_account_id');
        $table->decimal('balance')
            ->comment('當期餘額(除當期損益)');
        $table->decimal('transfer')
            ->comment('加項(出金轉存)');
        $table->decimal('withdraw')
            ->comment('減項(出金)');
        $table->decimal('computable', 10, 2)
            ->default(0)
            ->comment('可計算損益金額(上期餘額 - 出金 + 出金轉存)');
        $table->unsignedSmallInteger('quota')
            ->comment('權重');
        $table->decimal('profit');

        $table->unique(['invest_futures_id', 'invest_account_id'], 'unique_futures_account');
    }
}
