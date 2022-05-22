<?php

namespace App\Models\Invest;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * attribute
 * @property Carbon periodDate
 *
 * relation
 * @property Collection|null $InvestBalance
 */
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

//    protected $casts = [
//        'period' => 'date:Y-m'
//    ];

    public function getPeriodDateAttribute($period)
    {
        return Carbon::parse($this->period);
    }

    public function InvestBalance()
    {
        return $this->hasMany(InvestBalance::class, 'invest_futures_id', 'invest_futures_id');
    }
}
