<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestDetailTable extends Migration
{
    protected $table = 'invest_detail';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->date('occurred_at');
        $table->foreignId('invest_account_id');
        $table->enum('type', ['deposit', 'withdraw', 'profit', 'expense', 'transfer']);
        $table->decimal('amount', 10, 2);
        $table->string('note')->default('');

        $table->index(['occurred_at']);
        $table->index(['invest_account_id']);
    }
}
