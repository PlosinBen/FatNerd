<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvestAccountTable extends Migration
{
    protected $table = 'invest_account';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->string('alias');
        $table->foreignId('user_id');
        $table->enum('contract', [
            'normal',
            'friend'
        ])->default('normal')->nullable();

        $table->unique('user_id');
    }
}
