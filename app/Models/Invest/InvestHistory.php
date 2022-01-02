<?php

namespace App\Models\Invest;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InvestHistory extends Model
{
    protected $table = 'invest_history';

    protected $fillable = [
        'invest_account_id',
        'occurred_at',
        'type',
        'amount',
        'balance',
        'note',
    ];

    protected $casts = [
        'occurred_at' => 'date:Y-m-d',
        'period' => 'date:Y-m'
    ];

    public function scopePeriod($query, $value)
    {
        if (!$value instanceof Carbon) {
            $value = Carbon::parse($value);
        }

        $query->where('occurred_at', 'like', $value->format('Y-m%'));
    }

    public function scopeYear($query, $value)
    {
        $query->where('occurred_at', 'like', "{$value}%");
    }

    public function scopeInvestAccountId($query, $value)
    {
        $query->where('invest_account_id', $value);
    }

    public function InvestAccount()
    {
        return $this->belongsTo(InvestAccount::class);
    }

    public function InvestProfit()
    {
        return $this->hasOne(InvestFuturesProfit::class);
    }
}
