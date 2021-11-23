<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    protected $table = 'user';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->string('name');
        $table->string('avatar');
    }
}
