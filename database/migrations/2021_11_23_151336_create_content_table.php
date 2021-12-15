<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentTable extends Migration
{
    protected $table = 'content';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->string('slug');
        $table->boolean('is_markdown');
        $table->longText('body');
    }
}
