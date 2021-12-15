<?php

namespace App\Models\Invest;

use Illuminate\Database\Eloquent\Model;

class InvestHistory extends Model
{
    protected $table = 'invest_history';

    protected $fillable = [
        'period',
        'invest_user_id',
        'deposit',
        'withdraw',
        'profit',
        'transfer',
        'expense',
        'balance'
    ];
}
