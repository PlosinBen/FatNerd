<?php

namespace App\Models\Invest;

use Illuminate\Database\Eloquent\Model;

class InvestStatementFutures extends Model
{
    protected $table = 'invest_statement_futures';

    protected $fillable = [
        'period',
        'commitment',
        'open_interest',
        'profit',
        'real_commitment',
        'net_commitment',
        'distribution',
        'surplus_weight'
    ];
}
