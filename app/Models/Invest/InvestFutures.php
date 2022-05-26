<?php

namespace App\Models\Invest;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * attribute
 * @property Carbon $periodDate
 * @property string $profit
 * @property int $profit_per_quota
 *
 * scop
 * @method $this period()
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
        'deposit',
        'withdraw',
        'real_commitment',
        'net_commitment',
        'commitment_profit',
        'profit',
        'total_quota',
        'profit_per_quota'
    ];

    protected $casts = [
//        'period' => 'date:Y-m',
        'profit' => 'string',
        'total_quota' => 'int',
        'profit_per_quota' => 'int'
    ];

    public function setPeriodAttribute($period)
    {
        if ($period instanceof Carbon) {
            $period = $period->format('Y-m');
        }

        $this->attributes['period'] = $period;
    }

    public function getPeriodDateAttribute($period)
    {
        return Carbon::parse($this->period);
    }

    public function scopePeriod($query, $value)
    {
        if($value instanceof Carbon) {
            $value = $value->format('Y-m');
        }

        $query->where('period', $value);
    }

    public function InvestBalance()
    {
        return $this->hasMany(InvestBalance::class, 'period', 'period');
    }
}
