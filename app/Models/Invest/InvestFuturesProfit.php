<?php

namespace App\Models\Invest;

use Illuminate\Database\Eloquent\Model;

class InvestFuturesProfit extends Model
{
    protected $table = 'invest_futures_profit';

    protected $fillable = [
        'period',
        'invest_account_id',
        'computable',
        'quota',
        'profit'
    ];

    protected $casts = [
        'period' => 'date:Y-m'
    ];

    public function InvestFutures()
    {
        $this->belongsTo(InvestFutures::class);
    }

    public function InvestAccount()
    {
        $this->belongsTo(InvestAccount::class);
    }
}
