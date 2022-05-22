<?php

namespace App\Models\Invest;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $period
 * @property Carbon $periodDate
 */
class InvestBalance extends Model
{
    protected $table = 'invest_balance';

    protected $fillable = [
        'period',
        'invest_account_id',
        'balance',
        'withdraw',
        'transfer',
        'computable',
        'quota',
        'profit'
    ];

    public function getPeriodDateAttribute()
    {
        return Carbon::parse($this->period);
    }

    public function setPeriodDateAttribute($period)
    {
        if ($period instanceof Carbon) {
            $period = $period->format('Y-m');
        }

        $this->attributes['period'] = $period;
    }

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
