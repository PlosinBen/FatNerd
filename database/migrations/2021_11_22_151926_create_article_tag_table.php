<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticleTagTable extends Migration
{
    protected $table = 'article_tag';

    protected $timestamps = false;

    public function handle(Blueprint $table)
    {
        $table->foreignId('article_id');
        $table->foreignId('tag_id');
    }
}
