<?php

use App\Contract\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestStatementFuturesTable extends Migration
{
    protected $table = 'invest_statement_futures';

    public function handle(Blueprint $table)
    {
        $table->id();
        $table->date('period');
        $table->decimal('commitment', 10, 2)
            ->default(0)
            ->comment('期末權益');
        $table->decimal('open_interest', 10, 2)
            ->default(0)
            ->comment('未平倉損益');
        $table->decimal('profit', 10, 2)
            ->default(0)
            ->comment('沖銷損益');
        $table->decimal('real_commitment', 10, 2)
            ->default(0)
            ->comment('實質權益(期末權益 - 未平倉損益');

        $table->decimal('net_deposit_withdraw')
            ->default(0)
            ->comment('出入金淨額(當期總入金 - 當期總出金)');

        $table->decimal('profit_commitment', 10, 2)
            ->default(0)
            ->comment('權益損益(實質權益 - 出入金淨額 - 上期實質權益)');
        $table->decimal('distribution', 10, 2)
            ->default(0)
            ->comment('總分配金額 min(權益損益, 沖銷損益)');

        $table->unique('period');
    }
}
