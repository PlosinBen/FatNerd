<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestAccountDetailTable extends Migration
{
    protected $connection = 'invest';

    protected $table = 'account_detail';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->date('occurred_at');
        $table->foreignId('invest_user_id');
        $table->enum('type', ['deposit', 'withdraw', 'profit', 'expense', 'transfer']);
        $table->decimal('amount', 10, 2);
        $table->string('note');

        $table->index('invest_user_id');
    }
}
