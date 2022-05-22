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
        $table->foreignId('user_id')
            ->nullable();
        $table->unsignedSmallInteger('start_year_at')
            ->default(0);
        $table->enum('contract', [
            'normal',
            'friend'
        ])->nullable();

        $table->unique('user_id');
    }
}
