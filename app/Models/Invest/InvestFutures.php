<?php

namespace App\Models\Invest;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InvestFutures extends Model
{
    protected $table = 'invest_futures';

    protected $primaryKey = 'period';

    public $incrementing = false;

    protected $fillable = [
        'period',
        'commitment',
        'open_interest',
        'cover_profit',
        'real_commitment',
        'net_commitment',
        'profit',
        'surplus_weight'
    ];

    protected $casts = [
        'period' => 'date:Y-m'
    ];

    public function InvestFuturesProfits()
    {
        return $this->hasMany(InvestFuturesProfit::class, 'invest_futures_id', 'id');
    }
}
