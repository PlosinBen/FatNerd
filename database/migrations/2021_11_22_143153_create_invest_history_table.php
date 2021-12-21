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
        $table->date('period');
        $table->foreignId('invest_account_id');
        $table->decimal('deposit', 10, 2)->default(0);
        $table->decimal('withdraw', 10, 2)->default(0);
        $table->decimal('profit', 10, 2)->default(0);
        $table->decimal('transfer', 10, 2)->default(0);
        $table->decimal('expense', 10, 2)->default(0);
        $table->decimal('balance', 10, 2)->default(0);
        $table->smallInteger('quota')->unsigned()->default(0);

        $table->unique(['invest_account_id', 'period']);
    }
}
