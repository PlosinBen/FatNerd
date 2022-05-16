<?php

namespace App\Models\Invest;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InvestFuturesProfit extends Model
{
    protected $table = 'invest_futures_profit';

    protected $fillable = [
        'invest_futures_id',
        'invest_account_id',
        'balance',
        'withdraw',
        'transfer',
        'computable',
        'quota',
        'profit'
    ];

    public function scopePeriod($query, $value)
    {
        if ($value instanceof Carbon) {
            $value = $value->format('Y-m');
        }

        $query->where('period', $value);
    }

    public function InvestFutures()
    {
        return $this->belongsTo(InvestFutures::class);
    }

    public function InvestAccount()
    {
        return $this->belongsTo(InvestAccount::class, 'invest_account_id', 'id');
    }
}
