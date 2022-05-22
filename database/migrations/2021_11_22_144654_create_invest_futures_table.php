<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestFuturesTable extends Migration
{
    protected $table = 'invest_futures';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->char('period', 7);
        $table->decimal('commitment', 10, 2)
            ->default(0)
            ->comment('期末權益');
        $table->decimal('open_interest', 10, 2)
            ->default(0)
            ->comment('未平倉損益');

        $table->decimal('cover_profit', 10, 2)
            ->nullable()
            ->comment('沖銷損益');

        $table->decimal('deposit')
            ->default(0)
            ->comment('當期總入金');

        $table->decimal('withdraw')
            ->default(0)
            ->comment('當期總出金');

        $table->decimal('real_commitment', 10, 2)
            ->default(0)
            ->comment('實質權益(期末權益 - 未平倉損益');

        $table->decimal('commitment_profit', 10, 2)
            ->default(0)
            ->comment('權益損益(實質權益 - 出入金淨額[當期總入金 - 當期總出金] - 上期實質權益)');

        $table->decimal('profit', 10, 2)
            ->default(0)
            ->comment('最終損益 min(權益損益, 沖銷損益)');

        $table->smallInteger('total_quota')
            ->unsigned()
            ->default(0)
            ->comment('總分額數');

        $table->decimal('profit_per_quota')
            ->default(0)
            ->comment('每份額損益額');

        $table->unique('period');
    }
}
