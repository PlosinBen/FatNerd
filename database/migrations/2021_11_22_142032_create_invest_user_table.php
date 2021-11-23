<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvestUserTable extends Migration
{
    protected $connection = 'invest';

    protected $table = 'user';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->string('alias');
        $table->foreignId('user_id');
        $table->unique('user_id');
        $table->enum('contract', [
            'normal',
            'friend'
        ])->nullable();
    }
}
