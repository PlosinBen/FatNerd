<?php

namespace App\Models\Invest;

use App\Contract\EloquentScopeRuleTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * For Entity attr hinit
 * @property int $invest_account_id
 * @property int $serial_number
 * @property Carbon $occurred_at
 * @property string $type
 * @property string $amount
 * @property string $balance
 * @property string $note
 * @property Carbon $created_at
 *
 * For scope method hint
 * @method $this investAccountId(int $investAccountId)
 * @method $this period(Carbon $period)
 * @method $this occurredAfter(Carbon $period)
 */
class InvestHistory extends Model
{
    use EloquentScopeRuleTrait;

    protected $table = 'invest_history';

    protected $fillable = [
        'invest_account_id',
        'serial_number',
        'occurred_at',
        'type',
        'amount',
        'balance',
        'note',
    ];

    protected $casts = [
        'amount' => 'string',
        'balance' => 'string',
        'occurred_at' => 'date:Y-m-d',
        'period' => 'date:Y-m'
    ];

    public function setOccurredAtAttribute($period)
    {
        if ($period instanceof Carbon) {
            $period = $period->toDateString();
        }

        $this->attributes['occurred_at'] = $period;
    }

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

    public function scopeOccurredAfter($query, $value)
    {
        $this->moreThan($query, 'occurred_at', $value);
    }

    public function scopeOccurredBefore($query, $value)
    {
        $this->lessThan($query, 'occurred_at', $value);
    }

    public function InvestAccount()
    {
        return $this->belongsTo(InvestAccount::class);
    }

    public function InvestProfit()
    {
        return $this->hasOne(InvestBalance::class);
    }
}
