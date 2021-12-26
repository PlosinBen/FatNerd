<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestHistoryTable extends Migration
{
    protected $table = 'invest_history';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->foreignId('invest_account_id');
        $table->date('occurred_at')
            ->comment('帳務日期');

        $table->enum('type', ['deposit', 'withdraw', 'profit', 'expense', 'transfer'])
            ->comment('入帳類型');
        $table->decimal('amount', 10, 2)
            ->comment('入帳金額');
        $table->decimal('balance', 10, 2)
            ->comment('結餘金額');

        $table->string('note')
            ->default('');

        $table->index(['invest_account_id', 'occurred_at']);
    }
}
