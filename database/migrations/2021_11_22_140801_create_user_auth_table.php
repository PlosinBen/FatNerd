<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAuthTable extends Migration
{
    protected $table = 'user_auth';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->string('provider');
        $table->string('provider_user_id');
        $table->foreignId('user_id')
            ->nullable();
        $table->text('data');

        $table->unique(['provider', 'provider_user_id']);
    }
}
