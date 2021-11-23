<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestAccountTable extends Migration
{
    protected $connection = 'invest';

    protected $table = 'account';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->date('period');
        $table->foreignId('invest_user_id');
        $table->decimal('deposit', 10, 2);
        $table->decimal('withdraw', 10, 2);
        $table->decimal('profit', 10, 2);
        $table->decimal('transfer', 10, 2);
        $table->decimal('expense', 10, 2);
        $table->decimal('balance', 10, 2);

        $table->index('invest_user_id');
    }
}
