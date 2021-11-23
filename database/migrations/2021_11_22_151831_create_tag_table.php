<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTable extends Migration
{
    protected $table = 'tag';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->string('name');
    }
}
