<?php

use App\Contract\Migration;
use App\Data\InvestType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestProfitTable extends Migration
{
    protected $table = 'invest_profit';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->string('period');
        $table->foreignId('invest_account_id');
        $table->enum('type', InvestType::all());

        $table->decimal('computable', 10, 2)
            ->comment('有效權益');
        $table->smallInteger('quota')
            ->comment('份額');

        $table->decimal('profit', 10, 2)
            ->comment('分配損益');

        $table->unique(['period', 'invest_account_id', 'type'], 'unique_futures_account');
        $table->index('invest_account_id');
    }
}
