<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    protected $table = 'article';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->tinyInteger('state');
    }
}
