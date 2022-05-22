<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvestBalanceTable extends Migration
{
    protected $table = 'invest_balance';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->foreignId('invest_account_id');
        $table->string('period');

        $table->decimal('deposit', 10, 2)
            ->default(0)
            ->comment('入金');
        $table->decimal('withdraw', 10, 2)
            ->default(0)
            ->comment('出金');
        $table->decimal('profit', 10, 2)
            ->default(0)
            ->comment('損益');
        $table->decimal('expense', 10, 2)
            ->default(0)
            ->comment('費用');
        $table->decimal('transfer')
            ->default(0)
            ->comment('加項(出金轉存)');
        $table->decimal('balance', 10, 2)
            ->default(0)
            ->comment('結餘');

        $table->decimal('computable', 10, 2)
            ->default(0)
            ->comment('有效結餘(上期餘額 - 出金 + 出金轉存)');
        $table->unsignedSmallInteger('quota')
            ->default(0)
            ->comment('有效權重');
    }
}
